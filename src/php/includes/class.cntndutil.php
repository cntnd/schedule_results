<?php
// util class for cntnd - repos...
class CntndUtil
{
    private $_modulePath;

    public function setModulePath($modulePath){
        $this->_modulePath = $modulePath;
    }

    public function getRelativePath(){
        return $this->getRelativeModulePath($this->_modulePath);
    }

    public function getRelativeModulePath($absolutePath)
    {
        $start = strlen($_SERVER['DOCUMENT_ROOT']);
        $relativePath = substr($absolutePath, $start, strlen($absolutePath));
        return $relativePath;
    }

    public function getJs($files){
        $this->getAllJs($this->_modulePath, $files);
    }

// todo only "frontend" / "backend"...
    public function getAllJs($absolutePath, $files)
    {
        foreach ($files as &$jsFile) {
            $filename = $absolutePath . "js/" . $jsFile;
            $handle = fopen($filename, "r");
            $content = fread($handle, filesize($filename));
            echo '<script language="javascript" type="text/javascript">' . $content . '</script>';
            fclose($handle);
        }
    }

    public function getCss($files){
        $this->getAllCss($this->_modulePath, $files);
    }

// todo only "frontend" / "backend"...
    public function getAllCss($absolutePath, $files)
    {
        foreach ($files as &$cssFile) {
            $filename = $absolutePath . "css/" . $cssFile;
            $handle = fopen($filename, "r");
            $content = fread($handle, filesize($filename));
            echo '<style>' . $content . '</style>';
            fclose($handle);
        }
    }

// todo username and password as param?
    public function auth()
    {
        // The data to send to the API
        $postData = array(
            'username' => 'cntndapi',
            'password' => 'cj,fgP@[N{p}b.hH'
        );

        // Setup cURL
        $ch = curl_init('http://fclaenggasse.ch/cntnd/api/api.php/');
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        // Send the request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === FALSE) {
            die(curl_error($ch));
        }

        // Decode the response
        $responseData = json_decode($response, TRUE);

        // Print the date from the response
        return $responseData;
    }

    public function isJson($data=NULL) {
        if (!empty($data)) {
            @json_decode($data);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }
}
?>
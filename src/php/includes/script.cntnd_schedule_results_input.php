<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/base-64@1.0.0/base64.min.js"></script>
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.2/knockout-min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js" integrity="sha256-/GKyJ0BQJD8c8UYgf7ziBrs/QgcikS7Fv/SaArgBcEI=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.6.1/Sortable.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/knockout-sortable@1.2.0/build/knockout-sortable.min.js"></script>
<script language="javascript" type="text/javascript">
    $(document).ready(function() {
        // knockout
        function Team(data) {
            this.name = ko.observable(data.name);
            this.url = ko.observable(data.url);
            this.team = ko.observable(data.team);
            this.firstTeam = ko.observable(data.firstTeam);
            this.customTeam = ko.observable(data.customTeam);
            if (data.side===undefined){
                this.side = ko.observable('left');
            }
            else {
                this.side = ko.observable(data.side);
            }
            this.index = ko.observable(data.index);
        }

        function TeamViewModel() {
            // Data
            var self = this;
            self.newTeamText = ko.observable();
            self.availableTeams = scheduleTeams;

            // Sortable
            self.teamsLeft = ko.observableArray([]);
            self.teamsLeft.id='left';
            self.saveTeamsLeft = function(){ return ko.toJSON(self.teamsLeft); };
            self.teamsRight = ko.observableArray([]);
            self.teamsRight.id='right';
            self.saveTeamsRight = function(){ return ko.toJSON(self.teamsRight); };

            self.myDropCallback = function (arg) {
                arg.item.side(arg.targetParent.id);
                arg.item.index(arg.targetIndex);
                if (console) {
                    console.log("Moved '" + arg.item.name() + "' from " + arg.sourceParent.id + " (index: " + arg.sourceIndex + ") to " + arg.targetParent.id + " (index " + arg.targetIndex + ")");
                }
            };

            // Operations
            self.addTeam = function() {
                self.teamsLeft.push(new Team({ name: this.newTeamText() }));
                self.newTeamText('');
            };

            self.removeTeam = function(team) { self.teamsLeft.remove(team) };

            self.resetTeams = function(){
                var mappedTeamsLeft = $.map(teamsLeftJson, function(item) { return new Team(item) });
                self.teamsLeft(mappedTeamsLeft);
                var mappedTeamsRight = $.map(teamsRightJson, function(item) { return new Team(item) });
                self.teamsRight(mappedTeamsRight);
            };
            self.eraseTeams = function(){
                self.teamsLeft([]);
                self.teamsRight([]);
            };

            // Load initial state from server, convert it to Team instances, then populate self.tasks
            console.log(teamsLeftJson, $.isEmptyObject(teamsLeftJson));
            if (teamsLeftJson!==undefined && !$.isEmptyObject(teamsLeftJson)) {
                var mappedTeamsLeft = $.map(teamsLeftJson, function(item) { return new Team(item) });
                self.teamsLeft(mappedTeamsLeft);
            }
            else if (!$.isEmptyObject(self.availableTeams)) {
                var mappedTeamsLeft = $.map(self.availableTeams, function(item) { return new Team(item) });
                self.teamsLeft(mappedTeamsLeft);
            }
            if (teamsRightJson!==undefined) {
                var mappedTeamsRight = $.map(teamsRightJson, function(item) { return new Team(item) });
                self.teamsRight(mappedTeamsRight);
            }
        }

        ko.applyBindings(new TeamViewModel());
    });
</script>

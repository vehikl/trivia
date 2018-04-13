const Game = function () {
  var that = this

  var players          = new Array();
  var places           = new Array(6);
  var purses           = new Array(6);
  var inPenaltyBox     = new Array(6);

  var popQuestions     = new Array();
  var scienceQuestions = new Array();
  var sportsQuestions  = new Array();
  var rockQuestions    = new Array();

  var currentPlayer    = 0;
  var isGettingOutOfPenaltyBox = false;

  var didPlayerWin = function(){
    return !(purses[currentPlayer] == 6)
  };

  var map = {
    0: 'Pop',
    4: 'Pop',
    8: 'Pop',
    1: 'Science',
    5: 'Science',
    9: 'Science',
    2: 'Sports',
    6: 'Sports',
    10: 'Sports',
  }
  var currentCategory = function(){
    return map.hasOwnProperty(places[currentPlayer]) ? map[places[currentPlayer]] : 'Rock';
  };

  for(var i = 0; i < 50; i++){
    popQuestions.push("Pop Question "+i);
    scienceQuestions.push("Science Question "+i);
    sportsQuestions.push("Sports Question "+i);
    rockQuestions.push("Rock Question " + i);
  };

  this.isPlayable = function(howManyPlayers){
    return howManyPlayers >= 2;
  };

  this.add = function(playerName){
    players.push(playerName);
    places[this.howManyPlayers() - 1] = 0;
    purses[this.howManyPlayers() - 1] = 0;
    inPenaltyBox[this.howManyPlayers() - 1] = false;

    console.log(playerName + " was added");
    console.log("They are player number " + players.length);

    return true;
  };

  this.howManyPlayers = function(){
    return players.length;
  };

  this.getCurrentPlayer = function() {
    return players[currentPlayer]
  }

  var askQuestion = function(){
    if(currentCategory() == 'Pop')
      console.log(popQuestions.shift());
    if(currentCategory() == 'Science')
      console.log(scienceQuestions.shift());
    if(currentCategory() == 'Sports')
      console.log(sportsQuestions.shift());
    if(currentCategory() == 'Rock')
      console.log(rockQuestions.shift());
  };

  this.roll = function(roll){
    console.log(players[currentPlayer] + " is the current player");
    console.log("They have rolled a " + roll);

    if(inPenaltyBox[currentPlayer]){
      if(roll % 2 != 0){
        isGettingOutOfPenaltyBox = true;

        console.log(players[currentPlayer] + " is getting out of the penalty box");
        places[currentPlayer] = places[currentPlayer] + roll;
        if(places[currentPlayer] > 11){
          places[currentPlayer] = places[currentPlayer] - 12;
        }

        console.log(players[currentPlayer] + "'s new location is " + places[currentPlayer]);
        console.log("The category is " + currentCategory());
        askQuestion();
      }else{
        console.log(players[currentPlayer] + " is not getting out of the penalty box");
        isGettingOutOfPenaltyBox = false;
      }
    }else{

      places[currentPlayer] = places[currentPlayer] + roll;
      if(places[currentPlayer] > 11){
        places[currentPlayer] = places[currentPlayer] - 12;
      }

      console.log(players[currentPlayer] + "'s new location is " + places[currentPlayer]);
      console.log("The category is " + currentCategory());
      askQuestion();
    }
  };

  this.wasCorrectlyAnswered = function() {
    if (that.isNotEligibleToAnswer()) {
      that.changeTurn();
      return true;
    }

    return that.collectAndCheck();
  };

  this.putPlayerInPenaltyBox = function () {
    inPenaltyBox[currentPlayer] = true;
  }

  this.changeTurn = function() {
    currentPlayer = currentPlayer == players.length - 1 ? 0 : currentPlayer + 1
  }

  this.isNotEligibleToAnswer = function() {
    return inPenaltyBox[currentPlayer] && !isGettingOutOfPenaltyBox;
  }

  this.givePlayerCoin = function() {
    ++purses[currentPlayer];
  }

  this.collectAndCheck = function () {
    console.log('Answer was correct!!!!');
    this.givePlayerCoin()
    console.log(players[currentPlayer] + " now has " + purses[currentPlayer] + " Gold Coins.");
    var winner = didPlayerWin();
    this.changeTurn()
    return winner;
  }

  this.inPenaltyBox = function() {

  }
};

module.exports = Game

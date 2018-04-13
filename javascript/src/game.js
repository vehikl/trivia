const Game = function () {
  this.logs = [];
  this.log = (text) => {
    console.log(text);
    this.logs.push(text);
  }

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

  var didPlayerWin = () => {
    return !(purses[currentPlayer] === 6)
  };

  const categoryLookup = {
    0: 'Pop',
    1: 'Science',
    2: 'Sports',
    3: 'Rock',
    4: 'Pop',
    5: 'Science',
    6: 'Sports',
    7: 'Rock',
    8: 'Pop',
    9: 'Science',
    10: 'Sports',
    11: 'Rock',
  };

  var currentCategory = () => {
    const playerPlace = places[currentPlayer];
    return categoryLookup[playerPlace] || 'Rock';
  };

  this.createQuestion = (category, index) => `${category} Question ${index}`;
  for(var i = 0; i < 50; i++){
    popQuestions.push(this.createQuestion('Pop', i))
    scienceQuestions.push(this.createQuestion('Science', i))
    sportsQuestions.push(this.createQuestion('Sports', i))
    rockQuestions.push(this.createQuestion('Rock', i))
  };

  this.add = (playerName) => {
    players.push(playerName);
    const placeIndex = this.howManyPlayers() - 1;
    places[placeIndex] = 0;
    purses[placeIndex] = 0;
    inPenaltyBox[placeIndex] = false;

    this.log(playerName + " was added");
    this.log("They are player number " + players.length);

    return true;
  };

  this.howManyPlayers = () => players.length;


  var askQuestion = () => {
    switch(currentCategory()) {
      case 'Pop':
        this.log(popQuestions.shift());
        break;

      case 'Science':
        this.log(scienceQuestions.shift());
        break;

      case 'Sports':
        this.log(sportsQuestions.shift());
        break;

      case 'Rock':
        this.log(rockQuestions.shift());
        break;
    }
  };

  var addRollToCurrentPlayerPlace = (roll) => {
    this.log(players[currentPlayer] + " is getting out of the penalty box");
    places[currentPlayer] = (places[currentPlayer] + roll) % 12;
  }

  var rollInPenaltyNotFactorOfTwo = (roll) => {
    isGettingOutOfPenaltyBox = true;

    this.log(players[currentPlayer] + " is getting out of the penalty box");
    places[currentPlayer] = (places[currentPlayer] + roll) % 12;

    this.log(players[currentPlayer] + "'s new location is " + places[currentPlayer]);
    this.log("The category is " + currentCategory());
    askQuestion();
  };

  var rollInPenaltyWhenFactorOfTwo = (roll) => {
    this.log(players[currentPlayer] + " is not getting out of the penalty box");
    isGettingOutOfPenaltyBox = false;
  };

  this.roll = (roll) => {
    this.log(players[currentPlayer] + " is the current player");
    this.log("They have rolled a " + roll);

    if(inPenaltyBox[currentPlayer]){
      if(roll % 2 != 0){
        rollInPenaltyNotFactorOfTwo(roll);
      }else{
        rollInPenaltyWhenFactorOfTwo(roll);
      }
    }else{

      places[currentPlayer] = places[currentPlayer] + roll;
      if(places[currentPlayer] > 11){
        places[currentPlayer] = places[currentPlayer] - 12;
      }

      this.log(players[currentPlayer] + "'s new location is " + places[currentPlayer]);
      this.log("The category is " + currentCategory());
      askQuestion();
    }
  };

  this.wasCorrectlyAnswered = () => {
    if(inPenaltyBox[currentPlayer]){
      if(isGettingOutOfPenaltyBox){
        this.log('Answer was correct!!!!');
        purses[currentPlayer] += 1;
        this.log(players[currentPlayer] + " now has " +
                    purses[currentPlayer]  + " Gold Coins.");

        var winner = didPlayerWin();
        currentPlayer += 1;
        if(currentPlayer == players.length)
          currentPlayer = 0;

        return winner;
      }else{
        currentPlayer += 1;
        if(currentPlayer == players.length)
          currentPlayer = 0;
        return true;
      }



    }else{

      this.log("Answer was correct!!!!");

      purses[currentPlayer] += 1;
      this.log(players[currentPlayer] + " now has " +
                  purses[currentPlayer]  + " Gold Coins.");

      var winner = didPlayerWin();

      currentPlayer += 1;
      if(currentPlayer == players.length)
        currentPlayer = 0;

      return winner;
    }
  };

  this.wrongAnswer = () => {
		this.log('Question was incorrectly answered');
		this.log(players[currentPlayer] + " was sent to the penalty box");
		inPenaltyBox[currentPlayer] = true;

    currentPlayer += 1;
    if(currentPlayer == players.length)
      currentPlayer = 0;
		return true;
  };

  return this;
};

module.exports = Game

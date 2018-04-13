const Game = require('./game.js');
// const Player = require('./player.js');

function play(seed) {

    if (seed != undefined) {
        require('seedrandom')(seed, { global: true });
    }

    var notAWinner = false;

    var game = new Game();

    game.addPlayer('Chet');
    game.addPlayer('Pat');
    game.addPlayer('Sue');

    if (!game.hasEnoughPlayers()){ //move me into the game logic
      console.log("Not enough players to start the game")
      return;
    }

    do{

        game.roll(Math.floor(Math.random()*6) + 1);

        if(Math.floor(Math.random()*10) == 7){
            notAWinner = game.wrongAnswer();
        }else{
            notAWinner = game.wasCorrectlyAnswered();
        }

    } while(notAWinner);
}

module.exports = {
    play: play
}

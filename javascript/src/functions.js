const Game = require('./game.js');

function play(seed) {

    if (seed != undefined) {
        require('seedrandom')(seed, { global: true });
    }

    var notAWinner = false;

    var game = new Game();

    game.add('Chet');
    game.add('Pat');
    game.add('Sue');

    do {

        game.roll(Math.floor(Math.random()*6) + 1);

        if(Math.floor(Math.random()*10) == 7){
            console.log('Question was incorrectly answered');
            console.log(game.getCurrentPlayer() + " was sent to the penalty box");
            game.putPlayerInPenaltyBox()
            game.changeTurn();
            notAWinner = true
        }else{
            notAWinner = game.wasCorrectlyAnswered();
        }

    }while(notAWinner);
}

module.exports = {
    play: play
}

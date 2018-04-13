const Game = require('./game.js');

function play(seed) {

    if (seed != undefined) {
        require('seedrandom')(seed, { global: true });
    }

    var game = new Game();

    game.add('Chet');
    game.add('Pat');
    game.add('Sue');

    while(!game.hasWinner) {
        game.roll(Math.floor(Math.random()*6) + 1);
        game.checkAnswer();
        game.nextPlayer();
    }
}

module.exports = {
    play: play
}

const Game = require("./game.js");

function doRandomRollNumber() {
  return Math.floor(Math.random() * 6) + 1;
}

function wouldAnswerIncorrectly() {
  return Math.floor(Math.random() * 10) == 7;
}

function play(seed) {
  if (seed != undefined) {
    require("seedrandom")(seed, { global: true });
  }

  var notAWinner = false;

  var game = new Game();

  game.add("Chet");
  game.add("Pat");
  game.add("Sue");

  do {
    game.roll(doRandomRollNumber());

    if (wouldAnswerIncorrectly()) {
      notAWinner = game.wrongAnswer();
    } else {
      notAWinner = game.wasCorrectlyAnswered();
    }
  } while (notAWinner);

  return game;
}

module.exports = {
  play: play,
  doRandomRollNumber: doRandomRollNumber,
  wouldAnswerIncorrectly: wouldAnswerIncorrectly
};

const Game = require('./game.js')

const pop = new Category('Pop', 50)
const science = new Category('Science', 50)
const sports = new Category('Sports', 50)
const rock = new Category('Rock', 50)

const game = new Game([pop, science, sports, rock, pop, science, sports, rock, pop, science, sports])

const chet = new Player('Chet')
const pat = new Player('Pat')
const sue = new Player('Sue')

game.players = [chet, pat, sue]

function play(seed) {
    if (seed != undefined) {
        require('seedrandom')(seed, { global: true })
    }

    var notAWinner = false

    var game = new Game()

    game.add('Chet')
    game.add('Pat')
    game.add('Sue')

    do {
        game.roll(Math.floor(Math.random() * 6) + 1)

        if (Math.floor(Math.random() * 10) == 7) {
            notAWinner = game.wrongAnswer()
        } else {
            notAWinner = game.wasCorrectlyAnswered()
        }
    } while (notAWinner)
}

module.exports = {
    play: play
}

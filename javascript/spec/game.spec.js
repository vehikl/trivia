const Game = require('../src/game.js');
const runner = require('../src/functions.js');
const fs = require('fs');

describe("The test environment", function() {
  it("should pass", function() {
    expect(true).toBe(true);
  });

  it("should access game", function() {
    expect(Game).toBeDefined();
  });
});

describe("The game", function() {
  let log = [];

  beforeAll(function () {
    console.log = function (message) {
      log.push(message);
    }
  })

  afterEach(function () {
    log = [];
  })

  using("seeds 1 to 1000", new Array(1000).fill('').map((_, index) => [index + 1]), function (seed) {
    it(`should produce the expected output when seeded with ${seed}`, function () {
      runner.play(seed);
      expect(log.join("\n")).toEqual(fs.readFileSync(`./spec/fixtures/output-seeded-with-${seed}.txt`).toString());
    })
  })
});

function using(description, inputs, test) {
  inputs.forEach(function (input) {
    test.apply(this, input);
  });
}

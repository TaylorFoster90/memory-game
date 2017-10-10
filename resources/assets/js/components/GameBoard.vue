<template>
  <div>

    <h2 class="text-center mb-0">Score: {{ getScore() }}</h2>
    <h4 class="text-center">High Score: {{ savedScores[0] ? savedScores[0].score : '' }}</h4>
    <div id="gameboard" class="clearfix" v-bind:style="{ 'border-color': activeChoice.hex }">

      <div v-for="choice in choices" class="gameboard-block" v-bind:class="{ 'active' : isActiveChoice(choice) }"><span href="#" v-on:click="playerSelectChoice(choice.id)"  v-bind:style="{ 'background-color': choice.hex }" class="inner"></span></div>

    </div>

    <modal :name="modalName" :clickToClose="false">
      <div class="row">

        <div class="col-12">
          <div class="text-center">
            <h1>Welcome!</h1>
            <p>To get started, enter your name so we can keep track of your score.</p>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Name.." v-model="playerName">
            </div>
            <h3>Player Name: {{ playerName }}</h3>
            <button class="btn btn-md btn-success" v-on:click.prevent="startGame()">Start Game</button>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <h3>Past 5 Games</h3>
          <p v-for="score in localScores" class="mb-0">{{ score.name }} - {{ score.score }}</p>
        </div>

        <div class="col-12 col-md-6">
          <h3>Top 5 All Time Scores</h3>
          <p v-for="score, index in savedScores" class="mb-0">{{ index + 1 }}. {{ score.name }}: {{ score.score }}</p>
        </div>

      </div>
    </modal>


  </div>
</template>

<script>
  module.exports = {
    http: {
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    },
    props: [ 'user' ],
    data: function(){
      return {
        currentGame: {
          round: 0,
          inProgress: false,
          pattern: []
        },
        playerTurn: {
          active: false,
          choiceCount: 0
        },
        localScores: [ ],
        savedScores: [ ],
        activeChoice: { },
        choices: [
          {
            id: 1,
            name: 'blue',
            hex: '#0040ff'
          },
          {
            id: 2,
            name: 'yellow',
            hex: '#ffff4d'
          },
          {
            id: 3,
            name: 'green',
            hex: '#bfff00'
          },
          {
            id: 4,
            name: 'red',
            hex: '#ff4000'
          }
        ],
        modalName: 'game-modal',
        playerName: null
      };
    },
    created: function(){
      this.fetchStoredScores();
    },
    mounted : function(){
      this.nextRound(); // Setup round one when the component is mounted
      this.setLocalScores(); // Setup the local scores
      this.$modal.show(this.modalName); // Open the modal
    },
    methods: {
      startGame: function(){
        if( this.playerName ) {
          this.$modal.hide(this.modalName);
          this.currentGame.inProgress = true;
          this.executeRound();
        }else{
          alert('Name Please!');
        }
      },
      genRandBetween: function(min, max) {
        return Math.round(Math.random() * (max - min) + min);
      },
      nextRound: function(){
        var random = this.genRandBetween(0,4), // random number 0-4 corresponding to choices length
            choiceId = this.choices[random].id; // id of the choice being pushed to pattern
        this.currentGame.pattern.push(choiceId);
        this.playerTurn.choiceCount = 0;
        this.currentGame.round++;
      },
      /**
       * Simulates the current round
       * @param  int choiceId
       * @return void
       */
      roundSimulate: function(choiceId){
        var self = this;
        this.activeChoice = this.findChoice(choiceId);
        setTimeout(function(){
          self.activeChoice = {};
        }, 300);
      },
      /**
       * Executes the round simulation
       * @return void
       */
      executeRound: function() {
        var i = 0,
            self = this;
       this.playerTurn.active = false;
       var moves = setInterval(function(){
         self.roundSimulate(self.currentGame.pattern[i]);
         i++;
         if ( i >= self.currentGame.pattern.length ) {
           clearInterval(moves);
           self.playerTurn.active = true;
         }
       }, 600);
      },
      playerSelectChoice: function(choiceId){
        // If the game is not in progress and it is not the players turns, clicking doesn't do anything
        if( this.currentGame.inProgress && this.playerTurn.active ) {
          // Choosing the wrong choice will result is a gameover and a reset
          if( this.currentGame.pattern[this.playerTurn.choiceCount] == choiceId ){
            this.playerTurn.choiceCount++;
            // If the player turn is over, set a delay and start the next round
            if( this.playerTurn.choiceCount >= this.currentGame.pattern.length ){
              var self = this;
              setTimeout( function(){
                self.nextRound(); // setup round
                self.executeRound(); // execute round
              }, 500);
            }
          }else{
            this.gameOver();
          }
        }
      },
      gameOver: function(){
        alert('WRONG, GAME OVER');
        this.addLocalScore();
        this.addStoredScore();
        this.resetGame();
      },
      findChoice: function(choiceId) {
        var result;
        for( i = 0; i <= this.choices.length; i++ ) {
          if( this.choices[i].id == choiceId ) {
            result = this.choices[i];
            break;
          }
        }
        return result;
      },
      resetGame: function(){
        this.playerTurn = {
          active: false,
          choiceCount: 0
        };
        this.currentGame = {
          round: 0,
          pattern: [],
          inProgress: false
        };
        this.$modal.show(this.modalName);
        this.nextRound(); // Setup a new round
      },
      isActiveChoice: function(choice){
        if( this.activeChoice.id == choice.id ){
          return true;
        }else{
          return false;
        }
      },
      /**
       * Sets up the Vue model of the localStorage JSON
       * If the localStorage value isn't set, setup and empty JSON
       */
      setLocalScores: function(){
        if( localStorage.getItem("scores") ){
          var scores = JSON.parse(localStorage.getItem("scores"));
          this.localScores = scores;
        }else{
          localStorage.setItem("scores", "[]");
        }
      },
      addLocalScore: function(){
        var scores = JSON.parse(localStorage.getItem("scores"));
        // Keep the local scores to a limit of 5
        if( scores.length >= 5) {
          scores.pop();
        }
        scores.unshift({
          score: this.getScore(),
          name: this.playerName
        });
        localStorage.setItem("scores", JSON.stringify(scores));
        this.localScores = scores;
      },
      fetchStoredScores: function() {
        this.$http.get('/api/scores', { })
        .then((response) => {
          // Success
          this.savedScores = response.data;
        })
        .catch((err) => {
          // Error
        });
      },
      addStoredScore: function(){
        this.$http.post('/api/scores', {
          name: this.playerName,
          score: this.getScore()
         })
        .then((response) => {
          // The api endpoint gives us the top 20 scores back when we create a new one
          // So we can update our savedScores model
          this.savedScores = response.data;
        })
        .catch((err) => {
        });
      },
      getScore: function(){
        return this.currentGame.round - 1;
      }
    },
    computed: {

    },
    filters: {
       limit: function(arr, limit) {
         return arr.slice(0, 5)
      }
    }
  }
</script>

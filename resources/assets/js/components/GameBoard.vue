<template>
  <div id="gameboard" class="clearfix" v-bind:style="{ 'border-color': activeChoice.hex }">

    <div v-for="choice in choices" class="gameboard-block" v-bind:class="{ 'active' : isActiveChoice(choice) }"><span href="#" v-on:click="playerSelectChoice(choice.id)" class="inner"></span></div>

    <div class="text-center">
      <div class="btn-group">
        <button class="btn btn-md btn-success" v-if="!currentGame.inProgress" v-on:click.prevent="startGame()">Start Game</button>
      </div>
    </div>

  </div>
</template>

<script>
  module.exports = {
    props: [ 'user' ],
    data: function(){
      return {
        currentGame: {
          round: 0,
          inProgress: false,
          pattern: []
        },
        playerTurn: {
          active: true,
          choiceCount: 0
        },
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
        ]
      };
    },
    mounted : function(){
      this.nextRound(); // Setup round one when the component is mounted
    },
    methods: {
      startGame: function(){
        this.currentGame.inProgress = true;
        this.executeRound();
      },
      nextRound: function(){
        var random = Math.floor(Math.random() * 4), // random number 0-4 corresponding to choices length
            choiceId = this.choices[random].id; // id of the choice being pushed to pattern
        this.currentGame.pattern.push(choiceId);
        this.playerTurn.choiceCount = 0;
        this.currentGame.round++;
      },
      executeRound: function() {
        var counter = 0,
            self = this;
        function round()
        {
          if ( counter < self.currentGame.round ){
            var choice = self.findChoice(self.currentGame.pattern[counter]);
            self.activeChoice = choice;
            counter++;
            window.setTimeout(round, 700);
          }else{
            self.activeChoice = {};
          }
        }
        round();
      },
      startPlayerTurn: function(){
        this.activeChoice = {};
        this.currentRound.playersTurn = true;
      },
      playerSelectChoice: function(choiceId){
        if( this.currentGame.pattern[this.playerTurn.choiceCount] == choiceId ){
          this.playerTurn.choiceCount++;
          if( this.playerTurn.choiceCount >= this.currentGame.pattern.length ){
            var self = this;
            setTimeout( function(){
              self.nextRound();
              self.executeRound();
            }, 500);
          }
        }else{
          alert('WRONG, GAME OVER');
          this.resetGame();
        }
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
          active: true,
          choiceCount: 0
        };
        this.currentGame = {
          round: 0,
          pattern: []
        };
        this.nextRound(); // Setup a new round
      },
      isActiveChoice: function(choice){
        if( this.activeChoice.id == choice.id ){
          return true;
        }else{
          return false;
        }
      }
    },
    computed: {

    }
  }
</script>

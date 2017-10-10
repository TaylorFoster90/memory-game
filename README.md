# memory-game
A memory game, much like Simon, powered by VueJS and Laravel.


### Project Setup
- After cloning the repo, run `composer install` & `npm install`.
- Clone the `.env.example` and rename it to `.env`. Switch the `DB_*` variables there to match your environment.
- Run `php artisan key:generate`.
- Run `php artisan migrate` to setup the database.
- Run `npm run dev` to compile the assets.
- The game should be ready to run. If you are getting errors, please open an issue.


### Time Tracking

 - Project Directory Setup and Styling: 2 hours
 - Vue Component Coding: 4 hours
 - Researching: 2 hours
 - Adding Back End and API calls: 1 hour


#### Given More Time...

- Styling / UI could be optimized
- Sounds effects added when a user clicks a choice
- Discover more options for / optimize the `roundSimulate()` and `executeRound()` methods in the Vue model
- Add a 'Full Leaderboard' that shows ALL scores from database ( currently top 5 displayed )


### View Core Files
- [Vue Component](https://github.com/TaylorFoster90/memory-game/blob/master/resources/assets/js/components/GameBoard.vue)
- [Stylesheet](https://github.com/TaylorFoster90/memory-game/blob/master/resources/assets/sass/gameboard.scss)
- [Score API Controller](https://github.com/TaylorFoster90/memory-game/blob/master/app/Http/Controllers/API/ScoreApiController.php)
- [Score Model](https://github.com/TaylorFoster90/memory-game/blob/master/app/Score.php)

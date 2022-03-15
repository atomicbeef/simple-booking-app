### Usage
```
vendor/bin/sail php artisan migrate
vendor/bin/sail up -d
```
The page should now be available at http://localhost:80.

### Structure

The app utilizes Laravel as a REST API backend, MySQL for database storage, and Vue.js for the frontend. I made use of Sail and Docker to more easily set up a quick environment. This especially helped me because I use Windows on my desktop. The site is served to the client from a Laravel Blade template, but this template consists of a single Vue component. This is basically an SPA, which would have been better served by a real web server (rather than the built-in server), and without making use of Laravel's templating system.

### Bugs
I attempted to implement sorting by date of all the appointments in the frontend. Unfortunately, I could not figure out why this sorting was not working in time, and appointments are displayed in whatever order the backend serves the frontend. I also found that for some reason scheduling an appointment on the same date as the current date causes the appointment to appear in the future list (as intended), but in the past list upon a site refresh. I am confident that both of these issues could be resolved with a little more debugging.

### Comments
This was my first foray into full stack development, and while the code is most definitely not the cleanest I have written, it is functional. Overall, I think the website looks clean for the most part, though the editing interface could really make use of a modal window to be more user friendly. The backend was very straightforward to implement and took me the least amount of time. The frontend on the other hand, took me longer than expected to complete, which makes sense given my almost complete lack of experience in frontend development. Consequently, the frontend code is also the messiest, particularly the delete and update functions. If I were to redo this project, I would focus more on learning how to properly design the frontend and more about JavaScript, CSS, and Vue best practices. I would have liked to implement unit tests for the backend during development as well, but the simple requirements made it easy to test using `curl`.

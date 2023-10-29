Setup Run And Test
===========================
run this command to migrate and seed the database with 100 users and 100 lessons
```
php artisan migrate:fresh --seed
```
Create a database for tests
```
laravel_uts
```
you can use tinker to create dummy data and trigger events
```
php artisan tinker
```
to wrote comment on lesson
```
$user = User::find(1);
$lesson = Lesson::find(1);
$user->writeCommentOnLesson($lesson, 'This is my comment on the lesson.');
```
to watch a single lesson
```
$user = User::find(1);
$lesson = Lesson::find(1);
$user->watch($lesson);
```
to watch a collection of lessons
```
for ($i = 1; $i <= 50; $i++) {
    $lesson = Lesson::find($i);
    $user->watch($lesson);
}

```
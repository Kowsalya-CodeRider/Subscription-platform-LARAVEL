<!-- resources/views/emails/post-notification.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Post Notification</title>
</head>
<body>
    <h1>New Post Notification</h1>
    <p>Title: {{ $post->title }}</p>
    <p>Description: {{ $post->description }}</p>
    
    <p>Visit the website: {{ $post->website->url }}</p>

    <p>Thank you for subscribing to updates from {{ $post->website->name }}!</p>
</body>
</html>
 

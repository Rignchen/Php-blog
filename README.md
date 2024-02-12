# Blog PHP

This is a simple blog application written in PHP and stored on [GitHub](https://github.com/Rignchen/Php-blog)

## Installation
1. Clone the repository
2. Run `composer install`
3. Create a database `data.sqlite` and run the `data.sql` file to create the tables
4. Run `php -S localhost:8000 -t public` to start the server

## Website
- All posts are accessible with the link `http://localhost:8000/posts/<creator_name>/<post_name>`
- You can modify your posts with the link `http://localhost:8000/edit/<creator_name>/<post_name>`
- All users are accessible with the link `http://localhost:8000/users/<creator_name>`
- New posts can be created with the link `http://localhost:8000/new`

## Features
- Create, edit and delete posts
- Error and success messages

## License
This project is licensed under the CC-BY-NC-SA License - see the [LICENSE.md](LICENSE.md) file for details

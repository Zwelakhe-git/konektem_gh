# albums control
## from the controller, get
```php
$album = [
    'id' => 1,
    'name' => 'Album Name',
    'image_url' => '/path/to/cover.jpg',
    'release_year' => '2024-01-01',
    'description' => 'Description...',
    'genre' => 'Hip Hop',
    'artist_id' => 5,
    'likes' => 100,
    'downloads' => 50,
    'shares' => 30,
    'tracks' => [
        ['id' => 1, 'name' => 'Track 1', 'image_url' => '...', 'artist_name' => 'Artist']
    ]
];
$artists = [['id' => 1, 'name' => 'Artist 1'], ...];
```

## tracks have these fields
```js
{
    name: trackName,
    audioFile: audioFile,
    imageFile: imageFile,
    imagePreview: imagePreview,
    audioName: audioFile.name
}
```

## reference for album field names
```js
const albumName = document.getElementById('album_name').value.trim();
const genre = document.getElementById('genre').value.trim();
const releaseYear = document.getElementById('release_year').value;
const albumImage = document.getElementById('album_image').files[0];
const ownerName = document.getElementById('owner-name').value.trim();
const copyright = document.getElementById('copyright').checked;
const consent = document.getElementById('consent').checked;
```

## for create form validation starts from line 302. there is a reference to the api, fields and so on. its the last script tag

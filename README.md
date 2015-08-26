# Laasti/pagination

A small library to generate pagination.

## Installation

```
composer require laasti/pagination
```

## Usage

```php

$pagination = new Pagination($currentPage, $total, $perPage, $baseUrl, $neighbours);

echo '<a href="'.$pagination->first()->link().'">First</a>';
echo '<a href="'.$pagination->previous()->link().'">Previous</a>';
foreach ($pagination as $page) {
    if ($page->isActive()) {
        echo '<b>'.$page->number().'</b>';
    } else {
        echo '<a href="'.$page->link().'">'.$page->number().'</a>';
    }
}
echo '<a href="'.$pagination->next()->link().'">Next</a>';
echo '<a href="'.$pagination->last()->link().'">Last</a>';


```

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## History

See CHANGELOG.md for more information.

## Credits

Author: Sonia Marquette (@nebulousGirl)

## License

Released under the MIT License. See LICENSE.txt file.
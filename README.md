# Generic API

Playing with an idea of a Dynamic Model in Laravel.

Define a resource through a web view which can then be accessed through a CRUD API controller.

A Resource can be created which will create a new table within the database, Attributes can then be added, these add columns to the Resources table.

`DynamicModel` is then used by passing a resource name, e.g.
```php
$models = DynamicModel::forResource('example')->all();
```

It is possible to define attributes as encrypted, theses are stored in the database using `Crypt`.
The DynamicModel takes care of mutating and casting these attributes when loading and storing from the database.
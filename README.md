
# LTO (Laravel Transfer Object)

## Introduction

LTO helps you structure and manage data transfer in your application. It simplifies handling incoming request data, ensures type safety, and improves code clarity.

By leveraging DTOs, you can enforce a consistent data structure, prevent unexpected values, and seamlessly integrate with Laravel’s validation, models, and dependency injection.

🚀 **Why Use LTO?**

✅ Full IDE Auto-Completion Support → Since DTOs use typed properties and constructor parameters, your IDE (PhpStorm, VS Code, etc.) can provide full auto-completion while writing code.

✅ Strict Type Safety → Prevents unexpected data structures and makes your code more predictable.

✅ Improved Code Readability → Instead of working with raw request arrays, DTOs give a structured way to handle incoming data.

✅ Seamless Integration with Laravel → Works natively with request validation, dependency injection, and models.

---

## Installation

You can install LTO via Composer:

```sh
composer require serter35/lto
```

LTO supports **Laravel 10** and **Laravel 11** and requires **PHP 8.2** or **higher**.

## Quick Start

First, create a DTO class:

```sh
php artisan lto CommentDTO
```

Define the DTO class with the relevant properties.

For example, in the following example, we have created a DTO to store comments:

```php
<?php

namespace App\DTOs;

use SerterSerbest\LTO\Attributes\Request\FromBody;
use SerterSerbest\LTO\BaseDTO;

#[FromBody] 
class CommentDTO extends BaseDTO
{
    public function __construct(
        public ?string $title,
        public ?string $description
    ) {}
}
```

You can use this DTO class within a controller using Laravel's Method Injection feature:

```php
<?php

namespace App\Http\Controllers;

use App\DTOs\CommentDTO;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(CommentDTO $dto)
    {
        return Comment::create($dto->toArray());
    }        
}
```

This way, the `#[FromBody]` attribute automatically maps the `body` data of the Laravel request to the DTO.
Now, let's take a look at what kind of DTOs we can create for different request types.

---

## Creating a DTO

### 1. Using Request DTO

You can use the relevant attributes to bind DTOs with Laravel requests. 
If you want to use DTOs via Dependency Injection in controllers, you must specify an attribute (e.g., #[FromBody]). 
However, attributes are **not mandatory** for using DTOs—you can still instantiate them manually. 
The package supports the following request sources:

- **FromBody** → Gets data from the body of the request.
- **FromQuery** → Gets data from the query string.
- **FromRoute** → Gets data from route parameters.
- **FromRequest** → Automatically maps without specifying the source.


#### a) Using FromBody

```php
#[FromBody] 
class PostStoreDTO extends BaseDTO
{
    public function __construct(
        public ?string $title,
        public ?string $description
    ) {}
}
```

#### b) Using FromQuery

```php
#[FromQuery]
class QueryFilterDTO extends BaseDTO
{
    public function __construct(
        public ?string $sortColumn,
        public ?string $sortBy,
        public ?array $contains
    ) {}
}
```

Usage example:

```
[GET] http://localhost/users?sortColumn=created_at&sortBy=desc&contains[key]=name&contains[value]=john
```

#### c) Using a Complex DTO

You can gather different request parameters within a single DTO. 
In the example below, the #[FromBody] attribute is applied to the class itself, 
meaning that by default, all properties will be mapped from the request body. 
However, individual properties can override this behavior using different attributes such as #[FromQuery] or #[FromRoute].

```php
#[FromBody]
class CommentBodyDTO extends BaseDTO
{
    public function __construct(
        public ?string $title,
        public ?string $body,
        #[FromQuery] public ?bool $no_interaction,
        #[FromRoute('post')] public int $postId
    ) {}
}
```

#### d) Using FromRequest

To automatically scan all request sources:

```php
#[FromRequest]
class CommentBodyDTO extends BaseDTO
{
    public function __construct(
        public ?string $firstname,
        public ?string $lastname
    ) {}
}
```

🎯 Using Binding Keys in Attributes

In addition to the default behavior, attributes allow you to specify a binding key to map request data from a different field name.

For example, if the request body contains a field named "header", but you want to map it to $title in the DTO, you can do:

```php
#[FromBody('header')]
public ?string $title;
```
This tells the package to extract the "header" field from the request body and assign it to $title.
You can use this approach to rename request fields dynamically while keeping your DTO structure clean.

### 2. Creating a Simple DTO (Without Attributes)

Attributes are not required to define a DTO. You can create a DTO as a regular PHP class without using attributes.

However, if a DTO does not have an attribute, Laravel will not be able to automatically inject it via Dependency Injection (DI) in controllers. 
Instead, you must manually instantiate it.

```php
class PostStoreDTO extends BaseDTO
{
    public function __construct(
        public ?string $title,
        public ?string $description
    ) {}
}
```

🚀 **Note:** Even without attributes, you can still use methods like fromRequest(), fromArray(), and fromModel() to populate the DTO.
These methods are explained in the following sections.

---

## Using DTOs

### 1. Using Dependency Injection (DI)

You can pass DTOs into controller methods via **Dependency Injection**. 
This takes advantage of Laravel's automatic dependency resolution feature.

```php
public function store(UserCreateDTO $dto)
{
    return User::create($dto->toArray());
}  
```

> For details on how DTOs are created, see the Creating a DTO section.

### 2. Instantiating DTOs Manually

You can manually create a DTO instance using the new keyword.
This approach is useful when Dependency Injection is not an option, or when you need to create a DTO dynamically.

```php
$dto = new UserCreateDTO(
    name: 'John Doe',
    email: 'john.doe@example.com'
);
```

### 2. Using FromRequest

DTOs can be used to directly retrieve data from the incoming request object. 
This is a common approach to integrate request validation and data processing into the DTO.

```php
public function store(UserStoreFormRequest $request)
{
    $dto = UserCreateDTO::fromRequest($request);
    return $this->postService->create($dto);
}   
```

### 3. Using FromModel

When retrieving data from a model, you can convert the DTO to a model instance. 
This is ideal for using model data in a specific format.

```php
public function changeActive(User $user)
{
    return tap($user, function (User $user) {
        $user->update(['status' => 1]);
        $dto = MailBodyDTO::fromModel($user);
        $this->mailService->send($dto);
    });
} 
```

### 4. Using FromArray

This is used to convert an array of data into a DTO. It's a common way to work with external data sources.

```php
$dto = UserDTO::fromArray([
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
    'password' => 'secret'
]);
```

---

## Converting DTOs

You can convert a DTO to different formats using the following methods:

- **toArray()** → Converts to an array.
- **toCollection()** → Converts to a Laravel Collection.
- **toModel()** → Converts to a model instance.

Examples:

```php
$attributes = $dto->toArray();
```

```php
$collection = $dto->toCollection();
```

```php
$userModel = $dto->toModel(User::class);
```

---

## DTOs with Validation Support

### 1. Defining a Validatable DTO

To create a Validatable DTO class via the console, you can run the following command:
```sh
php artisan lto UserDTO --validatable
```

To add validation support to an existing DTO class, you can use the `Validatable` trait:

```php
#[FromBody]
class UserUpdateDTO extends BaseDTO
{
    use Validatable;
    
    public function __construct(
        public ?string $first_name,
        public ?string $last_name,
        public ?string $email
    ) {}
    
    protected function getValidationRules(): array
    {
        return [
            'first_name' => 'required|min:2|max:255'
        ];   
    }
}
```

To customize validation error messages:

```php
protected function getValidationMessages(): array
{
    return [
        'first_name.required' => ':attribute cannot be empty.'
    ];
}
```

```php
protected function getValidationAttributes(): array
{
    return [
        'first_name' => 'First Name'
    ];
}
```

### 2. Using DTO Validation

```php
public function update(User $user, UserUpdateDTO $dto)
{
    $validated = $dto->validate();
    return $user->update($validated);
}
```

To get only the validated data:

```php
$validatedArray = $dto->toValidatedArray();
$validatedCollection = $dto->toValidatedCollection();
$validatedModel = $dto->toValidatedModel(User::class);
```


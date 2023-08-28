# Laravel Model Diver

Devtool to visualize your Laravel project model relations in an automatically generated ERD diagram

> **Note**
> This package is in very early development stage, consider it pre-alpha

## Installation

You can install the package via composer:

```bash
composer require --dev nocte/modeldiver
```

> **Note**
> it's highly not recommended running this package in a production environment

## Usage

Just navigate to `[your-project-url]/modeldiver`

It might take a while to load the first time, depending on how many tables and models you have
You will have to manually drag each table in position

## Roadmap

- Generate tables in ther right places (not on top of each other)
- Improve arrows
- Auto regenerate diagram if models change
- Add button to manually regenerate diagram

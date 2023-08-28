# Laravel Model Diver

Devtool to visualize your Laravel project model relations in an automatically generated ERD diagram

![Screenshot 2023-08-28 at 17 31 43](https://github.com/0chak/modeldiver/assets/38387234/4c84c61e-8fe4-4ead-980a-96d3bc84f148)


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

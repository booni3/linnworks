# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Common Commands

### Testing
```bash
# Run all tests
./vendor/bin/phpunit

# Run tests with coverage
./vendor/bin/phpunit --coverage-text

# Run specific test
./vendor/bin/phpunit tests/LinnworksTest.php
```

### Dependency Management
```bash
# Install dependencies
composer install

# Update dependencies
composer update

# Require new package
composer require package/name
```

## Architecture Overview

This is a PHP library that provides a wrapper for the Linnworks API. The architecture follows a modular design pattern:

### Core Components

- **Main Entry Point**: `Linnworks` class in `src/Linnworks.php` - Factory class that provides access to all API modules
- **API Client**: `ApiClient` class in `src/Api/ApiClient.php` - Base HTTP client with authentication and request handling
- **API Modules**: Individual classes in `src/Api/` directory, each extending `ApiClient` and providing specific API functionality

### API Module Structure

Each API module (Orders, Inventory, Stock, etc.) extends the base `ApiClient` class and provides methods that correspond to specific Linnworks API endpoints:

- `Auth.php` - Authentication endpoints
- `Orders.php` - Order management (open orders, processed orders, order details)
- `Inventory.php` - Inventory operations
- `Stock.php` - Stock management
- `PostalServices.php` - Shipping and postal services
- `ReturnsRefunds.php` - Returns and refunds handling
- `OrderItems.php` - Order item operations

### Authentication Flow

1. The main `Linnworks` class automatically handles token refresh on instantiation
2. Uses application ID, secret, and token for authentication via `Auth::AuthorizeByApplication()`
3. Bearer token and server URL are obtained and used for subsequent API calls
4. All API modules receive the authenticated client, server URL, and bearer token

### Laravel Integration

- Includes Laravel service provider (`LinnworksServiceProvider`) for framework integration
- Publishes configuration file to `config/linnworks.php`
- Registers singleton instance with dependency injection
- Provides facade support via `LinnworksFacade`

### Configuration

Environment variables required:
- `LINNWORKS_APP_ID` - Application ID
- `LINNWORKS_SECRET` - Application Secret  
- `LINNWORKS_TOKEN` - API Token

### Usage Pattern

```php
// Direct instantiation
$linnworks = Linnworks::make($config);
$orders = $linnworks->orders()->getOpenOrders($fulfilmentCenter);

// Laravel usage (after service provider registration)
$orders = app(Linnworks::class)->orders()->getOpenOrders($fulfilmentCenter);
```

### Error Handling

Custom exceptions in `src/Exceptions/`:
- `LinnworksAuthenticationException` - Authentication failures
- `LinnworksResponseCouldNotBeParsed` - JSON parsing errors

### HTTP Client Details

- Uses Guzzle HTTP client for all requests
- Supports GET, POST, and POST JSON methods
- Automatic JSON response parsing with error handling
- Configurable timeout (default: 15 seconds)
- Consistent header handling for authentication and content types
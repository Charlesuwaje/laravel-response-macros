# Response Macros for Laravel

A lightweight Laravel package that provides reusable response macros for consistent API responses — supports both **JSON** and **SOAP** formats out of the box.

---

## Installation

```bash
composer require charlesuwaje/response-macros
```

Laravel will auto-discover the service provider. No manual registration needed.

---

## Choosing a Response Format

The package defaults to **JSON**. To switch to **SOAP**, publish the config file and change the format setting.

### Step 1 — Publish the config

```bash
php artisan vendor:publish --tag=response-macros-config
```

This creates `config/response-macros.php` in your Laravel project.

### Step 2 — Set your format

**Option A — via config file** (`config/response-macros.php`):

```php
return [
    'format' => 'json',  // or 'soap'
];
```

**Option B — via `.env`** (recommended):

```env
RESPONSE_MACROS_FORMAT=json
```

```env
RESPONSE_MACROS_FORMAT=soap
```

That's all. The same `response()` calls in your controllers work for both formats — **no controller code changes needed** when switching.

---

## Available Macros

All macros are available regardless of format.

| Macro | HTTP Status | Description |
|---|---|---|
| `response()->success($message, $data, $status)` | 200 | Standard success response |
| `response()->created($message, $data)` | 201 | Resource created |
| `response()->accepted($message, $data)` | 202 | Request accepted (async/queued) |
| `response()->noContent()` | 204 | Empty success (JSON only) |
| `response()->error($message, $data, $status)` | 400 | Generic error |
| `response()->unauthorized($message, $data)` | 401 | Not authenticated |
| `response()->forbidden($message, $data)` | 403 | Lacks permission |
| `response()->notFound($message, $data)` | 404 | Resource not found |
| `response()->methodNotAllowed($message, $data)` | 405 | HTTP method not allowed |
| `response()->conflict($message, $data)` | 409 | Conflict (e.g. duplicate resource) |
| `response()->validationError($message, $errors)` | 422 | Validation failed |
| `response()->tooManyRequests($message, $data)` | 429 | Rate limit exceeded |
| `response()->serverError($message, $data)` | 500 | Internal server error |
| `response()->serviceUnavailable($message, $data)` | 503 | Service unavailable |
| `response()->paginated($message, $data, $pagination)` | 200 | Paginated success response |

---

## Usage

Controller code is identical regardless of format:

```php
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(15);

        return response()->paginated('Users retrieved successfully', $users->items(), [
            'total'        => $users->total(),
            'per_page'     => $users->perPage(),
            'current_page' => $users->currentPage(),
            'last_page'    => $users->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = User::create($validated);

        return response()->created('User created successfully', $user->toArray());
    }

    public function show($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->notFound('User not found');
        }

        return response()->success('User found', $user->toArray());
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->notFound('User not found');
        }

        $user->delete();

        return response()->noContent();
    }
}
```

---

## JSON Response Format

Content-Type: `application/json`

**Success:**
```json
{
    "status": "success",
    "message": "User found",
    "data": { "id": 1, "name": "John" }
}
```

**Paginated:**
```json
{
    "status": "success",
    "message": "Users retrieved successfully",
    "data": [],
    "meta": {
        "pagination": {
            "total": 100,
            "per_page": 15,
            "current_page": 1,
            "last_page": 7
        }
    }
}
```

**Error:**
```json
{
    "status": "error",
    "message": "Something went wrong",
    "data": {}
}
```

**Validation error:**
```json
{
    "status": "validation_error",
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

---

## SOAP Response Format

Content-Type: `text/xml; charset=utf-8`

**Success** (`success`, `created`, `accepted`, `paginated`):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
  <SOAP-ENV:Body>
    <response>
      <status>success</status>
      <message>User found</message>
      <data>
        <id>1</id>
        <name>John</name>
      </data>
    </response>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

**Paginated** (includes `<meta>` block):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
  <SOAP-ENV:Body>
    <response>
      <status>success</status>
      <message>Users retrieved successfully</message>
      <data>
        <item><id>1</id></item>
      </data>
      <meta>
        <pagination>
          <total>100</total>
          <per_page>15</per_page>
          <current_page>1</current_page>
          <last_page>7</last_page>
        </pagination>
      </meta>
    </response>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

**Fault** (`error`, `unauthorized`, `forbidden`, `notFound`, `validationError`, etc.):

Error macros map to a `SOAP-ENV:Fault`. Client errors (4xx) use `faultcode: Client`, server errors (5xx) use `faultcode: Server`.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
  <SOAP-ENV:Body>
    <SOAP-ENV:Fault>
      <faultcode>Client</faultcode>
      <faultstring>User not found</faultstring>
    </SOAP-ENV:Fault>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

**Validation fault** (includes `<detail>` with field errors):
```xml
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
  <SOAP-ENV:Body>
    <SOAP-ENV:Fault>
      <faultcode>Client</faultcode>
      <faultstring>Validation failed</faultstring>
      <detail>
        <email>
          <item>The email field is required.</item>
        </email>
      </detail>
    </SOAP-ENV:Fault>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

---

## SOAP Fault Code Reference

| Macro | SOAP Fault Code | HTTP Status |
|---|---|---|
| `error()` | `Client` | 400 |
| `unauthorized()` | `Client` | 401 |
| `forbidden()` | `Client` | 403 |
| `notFound()` | `Client` | 404 |
| `methodNotAllowed()` | `Client` | 405 |
| `conflict()` | `Client` | 409 |
| `validationError()` | `Client` | 422 |
| `tooManyRequests()` | `Client` | 429 |
| `serverError()` | `Server` | 500 |
| `serviceUnavailable()` | `Server` | 503 |

---

## Contributing

PRs and suggestions are welcome. If you find a bug or want to add new macros, open an issue or submit a pull request.

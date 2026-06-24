# Response Macros for Laravel

A lightweight Laravel package that provides reusable JSON response macros to keep your API responses consistent and developer-friendly.

---

## Installation

```bash
composer require charlesuwaje/response-macros
```

Laravel will auto-discover the service provider. No manual registration needed.

---

## Available Macros

| Macro | Status | Description |
|---|---|---|
| `response()->success($message, $data, $status)` | 200 | Standard success response |
| `response()->created($message, $data)` | 201 | Resource created |
| `response()->accepted($message, $data)` | 202 | Request accepted (async/queued) |
| `response()->noContent()` | 204 | Empty success response |
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

## Response Format

**Success:**
```json
{
    "status": "success",
    "message": "Users retrieved successfully",
    "data": []
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

## Contributing

PRs and suggestions are welcome. If you find a bug or want to add new macros, open an issue or submit a pull request.

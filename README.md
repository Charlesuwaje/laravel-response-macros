# Response Macros for Laravel

A lightweight Laravel package that provides reusable JSON response macros — `success`, `error`, `created`, and more — to keep your API responses consistent and developer-friendly.

---

## Installation

```bash
composer require charlesuwaje/response-macros
```

Laravel will auto-discover the service provider. No manual registration needed.

---

## Development Installation (local or GitHub repo)

Add to your `composer.json`:

```json
{
    "minimum-stability": "dev",
    "prefer-stable": true,
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/Charlesuwaje/response-macros"
        }
    ]
}
```

Then run:

```bash
composer require charlesuwaje/response-macros:@dev
```

---

## Available Macros

| Macro | Status | Description |
|---|---|---|
| `response()->success($message, $data)` | 200 | Standard success response |
| `response()->created($message, $data)` | 201 | Resource created |
| `response()->error($message, $data, $status)` | 400 | Generic error |
| `response()->unauthorized($message)` | 401 | Not authenticated |
| `response()->forbidden($message)` | 403 | Lacks permission |
| `response()->notFound($message)` | 404 | Resource not found |
| `response()->validationError($message, $errors)` | 422 | Validation failed |
| `response()->noContent()` | 204 | Empty success response |

---

## Usage

```php
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->success('Users retrieved successfully', $users->toArray());
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

Success responses:

```json
{
    "status": "success",
    "message": "Your message here",
    "data": {}
}
```

Error responses:

```json
{
    "status": "error",
    "message": "Your error message here",
    "data": {}
}
```

Validation error responses:

```json
{
    "status": "validationError",
    "message": "Validation failed",
    "errors": {}
}
```

---

## Contributing

PRs and suggestions are welcome. If you find a bug or want to add new macros, open an issue or submit a pull request.

# specs/authentication.md

> **Feature:** Authentication
> **Epic:** IP — Epic 1
> **Branch:** `feature/authentication`
> **Status:** To Do
> **AI Mode:** Plan → Build

---

## 1. Feature Goal

Implement a complete authentication system using **Laravel Breeze**.
Users must be able to register, log in, and log out securely.
All application routes (domains, concepts, generations) must be protected and inaccessible to guests.

---

## 2. User Stories

| ID | As a... | I want to... | So that... |
|---|---|---|---|
| US-1 | Guest | Register with name, email, password | I can create my personal account |
| US-2 | Guest | Log in with email and password | I can access my dashboard |
| US-3 | User | Log out | My session is ended securely |
| US-4 | Guest | Be redirected to login if I access a protected route | I cannot see other users' data |

---

## 3. Routes

```php
// routes/web.php

// Auth routes — provided by Breeze
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Protected routes — everything inside this group requires auth
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    // domains, concepts, generations routes go here
});
```

---

## 4. Controllers

### Auth/RegisteredUserController
| Method | Responsibility |
|---|---|
| `create()` | Return registration view |
| `store()` | Validate input → hash password → create User → login → redirect to dashboard |

### Auth/AuthenticatedSessionController
| Method | Responsibility |
|---|---|
| `create()` | Return login view |
| `store()` | Validate credentials → authenticate → redirect to dashboard |
| `destroy()` | Logout → invalidate session → redirect to login |

---

## 5. Model — User.php

```php
protected $fillable = ['name', 'email', 'password'];

protected $hidden = ['password', 'remember_token'];

protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];
}

public function domains(): HasMany
{
    return $this->hasMany(Domain::class);
}
```

---

## 6. Validation Rules

### Registration
| Field | Rules |
|---|---|
| `name` | required, string, max:255 |
| `email` | required, email, max:255, unique:users |
| `password` | required, min:8, confirmed |

### Login
| Field | Rules |
|---|---|
| `email` | required, email |
| `password` | required |

---

## 7. Views

```
resources/views/
├── auth/
│   ├── register.blade.php      — registration form
│   ├── login.blade.php         — login form
├── layouts/
│   ├── app.blade.php           — authenticated layout (nav + content)
│   ├── guest.blade.php         — guest layout (centered card)
├── dashboard.blade.php         — post-login landing page
```

---

## 8. Security Checklist

- [x] Passwords hashed via `bcrypt` (Laravel default with `'password' => 'hashed'` cast)
- [x] CSRF token on every form (`@csrf`)
- [x] `auth` middleware applied to all non-auth routes
- [x] Session regenerated on login (`session()->regenerate()`)
- [x] Session invalidated on logout

---

## 9. Laravel Commands

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run dev
php artisan migrate
```

---

## 10. Edge Cases & Error States

| Scenario | Expected Behavior |
|---|---|
| Email already registered | Validation error: "The email has already been taken." |
| Wrong password on login | Validation error: "These credentials do not match our records." |
| Guest accesses `/dashboard` | Redirected to `/login` |
| Guest accesses `/domains` | Redirected to `/login` |
| Passwords don't match on register | Validation error: "The password field confirmation does not match." |

---

## 11. Commits for This Feature

```bash
feat(auth): install Laravel Breeze — AI-assisted planning
feat(auth): implement registration page and store logic — AI-assisted
feat(auth): implement login and logout — AI-assisted
feat(auth): protect all application routes with auth middleware — AI-assisted
```
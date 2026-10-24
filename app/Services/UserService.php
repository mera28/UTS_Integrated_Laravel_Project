<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Retrieve paginated users with role and search filters.
     */
    public function getPaginatedUsers(?string $search = null, ?string $role = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = User::latest();

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (! empty($role)) {
            $query->where('role', $role);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Create a new user with hashed password.
     *
     * @param array<string, mixed> $data
     */
    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['email_verified_at'] = now();
        return User::create($data);
    }

    /**
     * Update user details and optionally update password.
     *
     * @param array<string, mixed> $data
     */
    public function updateUser(User $user, array $data): User
    {
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return $user;
    }

    /**
     * Safely delete user, preventing self-deletion by active admin.
     *
     * @return array{success: bool, message: string}
     */
    public function deleteUser(User $user, User $currentUser): array
    {
        if ($user->id === $currentUser->id) {
            return [
                'success' => false,
                'message' => 'Tindakan ditolak! Anda tidak dapat menghapus akun Anda sendiri saat sedang masuk ke sistem.',
            ];
        }

        $userName = $user->name;
        $userRole = strtoupper($user->role);
        $user->delete();

        return [
            'success' => true,
            'message' => "Pengguna {$userName} [{$userRole}] berhasil dihapus dari sistem.",
        ];
    }
}

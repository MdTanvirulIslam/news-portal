<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::withCount('posts')->orderBy('created_at', 'desc');

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('role', function ($user) {
                    $colors = [
                        'admin' => 'danger',
                        'editor' => 'warning',
                        'reporter' => 'info',
                        'contributor' => 'secondary'
                    ];
                    return '<span class="badge badge-' . $colors[$user->role] . '">' . ucfirst($user->role) . '</span>';
                })
                ->addColumn('posts_count', function ($user) {
                    return $user->posts_count;
                })
                ->addColumn('status', function ($user) {
                    return $user->is_active
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-secondary">Inactive</span>';
                })
                ->addColumn('joined', function ($user) {
                    return $user->created_at->format('M d, Y');
                })
                ->addColumn('action', function ($user) {
                    if ($user->id === auth()->id()) {
                        return '<span class="text-muted">Current User</span>';
                    }
                    return '
                        <div class="action-btns d-flex gap-2 justify-content-center">
                            <a href="' . route('admin.users.edit', $user->id) . '" class="btn btn-sm btn-icon btn-success">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteUser(' . $user->id . ')" class="btn btn-sm btn-icon btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['role', 'status', 'action'])
                ->make(true);
        }

        return view('admin.users.index');
    }

    public function edit(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.profile.edit');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.profile.edit')->with('error', 'Use profile page to edit your own account');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,editor,reporter,contributor',
            'password' => 'nullable|min:8|confirmed',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        //ActivityLog::log('updated', "Updated user: {$user->name}", $user);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete your own account'], 400);
        }

        if ($user->posts()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete user with posts'], 400);
        }

        ActivityLog::log('deleted', "Deleted user: {$user->name}", $user);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }
}

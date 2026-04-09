@extends('layouts.app')

@section('header', 'User Management')

@section('content')
    <div class="flex flex-col gap-10" x-data="userManager()">

        <!-- Header Actions -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black tracking-tight text-slate-900">Team Members</h2>
                <p class="mt-1 text-sm font-medium text-slate-400">Manage system users and their access roles</p>
            </div>
            <button @click="openCreateModal()"
                class="flex items-center gap-2 rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-black text-white shadow-lg shadow-indigo-500/30 transition-all hover:bg-indigo-700 hover:scale-[1.02]">
                <i class="fas fa-user-plus"></i>
                Add User
            </button>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-2 gap-6">
            <div class="flex items-center gap-5 rounded-[2rem] bg-white border border-slate-100 p-6 shadow-sm">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-xl text-indigo-600">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <h4 class="text-3xl font-black text-slate-900">{{ $users->where('role', 'Admin')->count() }}</h4>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Admins</p>
                </div>
            </div>
            <div class="flex items-center gap-5 rounded-[2rem] bg-white border border-slate-100 p-6 shadow-sm">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-50 text-xl text-purple-600">
                    <i class="fas fa-cash-register"></i>
                </div>
                <div>
                    <h4 class="text-3xl font-black text-slate-900">{{ $users->where('role', 'Cashier')->count() }}</h4>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Cashiers</p>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="rounded-[3rem] bg-white shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 pb-0">
                <h3 class="text-lg font-black text-slate-900">All Users</h3>
            </div>
            <div class="p-8">
                @if($users->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-slate-300">
                        <i class="fas fa-users text-5xl mb-4"></i>
                        <p class="text-sm font-bold text-slate-400">No users found.</p>
                    </div>
                @else
                    <div class="overflow-hidden rounded-2xl border border-slate-100">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-left">
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">User
                                    </th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Email
                                    </th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Role
                                    </th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Joined
                                    </th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-slate-400">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($users as $user)
                                    <tr class="transition-colors hover:bg-slate-50/50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff&size=80"
                                                    class="h-10 w-10 rounded-xl object-cover shadow-sm" alt="{{ $user->name }}">
                                                <div>
                                                    <p class="font-black text-slate-900">{{ $user->name }}</p>
                                                    @if($user->id === auth()->id())
                                                        <span class="text-[9px] font-black uppercase text-indigo-500">You</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 font-semibold">{{ $user->email }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-[10px] font-black uppercase tracking-widest
                                                {{ $user->role === 'Admin' ? 'bg-indigo-50 text-indigo-700' : 'bg-purple-50 text-purple-700' }}">
                                                <i
                                                    class="fas {{ $user->role === 'Admin' ? 'fa-user-shield' : 'fa-cash-register' }} text-[8px]"></i>
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-slate-400 font-semibold">
                                            {{ $user->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <button @click="openEditModal({{ $user->toJson() }})"
                                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition-all hover:bg-indigo-100 hover:text-indigo-600">
                                                    <i class="fas fa-pen text-xs"></i>
                                                </button>
                                                @if($user->id !== auth()->id())
                                                    <button @click="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition-all hover:bg-rose-100 hover:text-rose-600">
                                                        <i class="fas fa-trash-alt text-xs"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- ── Create / Edit Modal ─────────────────────────────────────── -->
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
            @keydown.escape.window="showModal = false">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModal = false"></div>

            <!-- Panel -->
            <div class="relative w-full max-w-lg rounded-[2.5rem] bg-white shadow-2xl" x-transition>
                <div class="p-10">
                    <div class="mb-8 flex items-center justify-between">
                        <h2 class="text-2xl font-black text-slate-900" x-text="editing ? 'Edit User' : 'Add New User'"></h2>
                        <button @click="showModal = false"
                            class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-500 hover:bg-slate-200 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form :action="editing ? '/users/' + form.id : '/users'" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="_method" value="PUT" :disabled="!editing">

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Full
                                Name</label>
                            <input type="text" name="name" x-model="form.name" required
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5 text-sm font-semibold text-slate-900 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                                placeholder="e.g. Jane Smith">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Email
                                Address</label>
                            <input type="email" name="email" x-model="form.email" required
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5 text-sm font-semibold text-slate-900 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                                placeholder="e.g. jane@shop.com">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                Password <span x-show="editing" class="normal-case font-medium text-slate-300">(leave blank
                                    to keep current)</span>
                            </label>
                            <input type="password" name="password" :required="!editing"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5 text-sm font-semibold text-slate-900 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                                placeholder="Min. 6 characters">
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Role</label>
                            <select name="role" x-model="form.role" required
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5 text-sm font-semibold text-slate-900 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                                <option value="Admin">Admin — Full access</option>
                                <option value="Cashier">Cashier — POS only</option>
                            </select>
                        </div>

                        <!-- Role Permissions Summary -->
                        <div class="rounded-2xl p-4 transition-colors"
                            :class="form.role === 'Admin' ? 'bg-indigo-50 border border-indigo-100' : 'bg-purple-50 border border-purple-100'">
                            <p class="text-[10px] font-black uppercase tracking-widest mb-2"
                                :class="form.role === 'Admin' ? 'text-indigo-500' : 'text-purple-500'">
                                Permissions
                            </p>
                            <ul class="space-y-1 text-xs font-semibold"
                                :class="form.role === 'Admin' ? 'text-indigo-700' : 'text-purple-700'">
                                <template x-if="form.role === 'Admin'">
                                    <div class="space-y-1">
                                        <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-500"></i>
                                            Full dashboard & reports</li>
                                        <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-500"></i>
                                            Inventory & product management</li>
                                        <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-500"></i>
                                            User & settings management</li>
                                        <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-500"></i>
                                            POS terminal access</li>
                                    </div>
                                </template>
                                <template x-if="form.role === 'Cashier'">
                                    <div class="space-y-1">
                                        <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-500"></i>
                                            Personal sales dashboard</li>
                                        <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-500"></i>
                                            POS terminal access</li>
                                        <li class="flex items-center gap-2"><i class="fas fa-check text-emerald-500"></i>
                                            View customers</li>
                                        <li class="flex items-center gap-2"><i class="fas fa-times text-rose-400"></i> No
                                            inventory/admin access</li>
                                    </div>
                                </template>
                            </ul>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit"
                                class="flex-1 rounded-2xl bg-indigo-600 py-4 text-sm font-black text-white shadow-lg shadow-indigo-500/30 transition-all hover:bg-indigo-700 hover:scale-[1.01]"
                                x-text="editing ? 'Save Changes' : 'Create User'">
                            </button>
                            <button type="button" @click="showModal = false"
                                class="rounded-2xl border border-slate-200 bg-slate-50 px-6 py-4 text-sm font-black text-slate-600 transition-all hover:bg-slate-100">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ── Delete Confirm Modal ───────────────────────────────────── -->
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
            @keydown.escape.window="showDeleteModal = false">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            <div class="relative w-full max-w-sm rounded-[2.5rem] bg-white p-10 shadow-2xl text-center" x-transition>
                <div
                    class="flex h-20 w-20 items-center justify-center rounded-full bg-rose-50 text-4xl text-rose-500 mx-auto mb-6">
                    <i class="fas fa-user-times"></i>
                </div>
                <h2 class="text-xl font-black text-slate-900 mb-2">Delete User?</h2>
                <p class="text-sm text-slate-500 mb-8">
                    You are about to remove <strong x-text="deleteTarget.name" class="text-slate-700"></strong>.
                    This action cannot be undone.
                </p>
                <form :action="'/users/' + deleteTarget.id" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="flex-1 rounded-2xl bg-rose-600 py-4 text-sm font-black text-white shadow-lg shadow-rose-500/30 transition-all hover:bg-rose-700">
                            Yes, Delete
                        </button>
                        <button type="button" @click="showDeleteModal = false"
                            class="rounded-2xl border border-slate-200 bg-slate-50 px-6 py-4 text-sm font-black text-slate-600 transition-all hover:bg-slate-100">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    @push('js')
        <script>
            function userManager() {
                return {
                    showModal: false,
                    showDeleteModal: false,
                    editing: false,
                    form: { id: null, name: '', email: '', role: 'Cashier' },
                    deleteTarget: { id: null, name: '' },

                    openCreateModal() {
                        this.editing = false;
                        this.form = { id: null, name: '', email: '', role: 'Cashier' };
                        this.showModal = true;
                    },

                    openEditModal(user) {
                        this.editing = true;
                        this.form = { id: user.id, name: user.name, email: user.email, role: user.role };
                        this.showModal = true;
                    },

                    confirmDelete(id, name) {
                        this.deleteTarget = { id, name };
                        this.showDeleteModal = true;
                    }
                }
            }
        </script>
    @endpush
@endsection
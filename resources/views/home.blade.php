<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Page</title>
</head>
<body>
    @if(session('success'))
        <div style="background: rgb(79, 212, 79); color: black; padding: 10px; border-radius: 6px; margin-bottom: 10px;">
            {{session('success')}}
        </div>
    @endif

    @if(session('error'))
        <div style="background: rgb(255, 142, 142); color: black; padding: 10px; border-radius: 6px; margin-bottom: 10px;">
            {{session('error')}}
        </div>
    @endif
    
    @if($errors->any())
        <div style="background: rgb(255, 142, 142); color: black; padding: 10px; border-radius: 6px; margin-bottom: 10px;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @auth 
        {{-- @if(!auth()->user()->is_verified)
            <div style="border: 3px solid black; padding: 10px; margin-bottom: 20px;">
                <h2>Enter OTP</h2>
                <p>An OTP has been sent to your email: <b>{{ auth()->user()->email }}</b></p>
                <form action="{{ route('verify.otp', auth()->user()->id) }}" method="POST">
                    @csrf
                    <input type="text" name="otp" placeholder="Enter OTP" required>
                    <button type="submit">Verify OTP</button>
                </form>
            </div>
        @else --}}
            <h2>Welcome, {{ auth()->user()->name }}</h2>
            <p>Your email is <b>{{ auth()->user()->email }}</b></p>
            <form action="/logout" method="POST">
                @csrf
                <button>Logout</button>
            </form>

            @if(auth()->user()->isadmin)
                <div style="border: 3px solid black">
                    <h2>Users list</h2>
                    
                    @if(!empty($users) && $users->count() > 0)
                        <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th>ID></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Is Admin?</th>
                                    <th>Actions</th>
                                    <th>Send OTP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <form action="{{ url('/admin/users/' . $user->id)}}" method="POST">
                                        @csrf
                                        <td>{{ $user->id }}</td>
                                        <td><input name="updatename" type="text" placeholder="name" value="{{$user->name}}"></td>
                                        <td><input name="updateemail" type="email" placeholder="email" value="{{$user->email}}"></td>
                                        <td><input name="updatepassword" type="text" placeholder="password" value="{{$user->name}}"></td>
                                        <td><input name="updateisadmin" type="checkbox" {{ $user->isadmin ? 'checked': '' }}></td>
                                        <td style="text-align:">
                                            <input type="submit" name="action" value="edit">
                                            <input type="submit" name="action" value="delete" 
                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                        </td>
                                    </form>
                                    {{-- <form action="{{ url('/admin/users/' . $user->id . '/send-otp') }}" method="POST"> --}}
                                    <form action="{{ url('/admin/users/' . $user->id . '/send-otp') }}" method="POST">
                                        @csrf
                                        <td style="text-align:center;"><button type="submit">Send</button></td>
                                    </form>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No users found.</p>
                    @endif
                </div>
            @endif
        {{-- @endif      --}}
    @else
        <div style="border: 3px solid black">
            <h2>Register</h2>
            <form action="/register" method="POST">
                @csrf
                <input name="name" type="text" placeholder="name">
                <input name="email" type="email" placeholder="email">
                <input name="password" type="password" placeholder="password">
                <label><input name=isadmin type="checkbox"> Is Admin?</label>
                {{-- <input name="otp" type="text" placeholder="Enter OTP"> --}}
                <button>Register</button>
            </form>
        </div>
        <div style="border: 3px solid black">
            <h2>Login</h2>
            <form action="/login" method="POST">
                @csrf
                <input name="loginname" type="text" placeholder="name">
                <input name="loginpassword" type="password" placeholder="password">
                {{-- <input name="loginotp" type="text" placeholder="Enter OTP"> --}}
                <button>Login</button>
            </form>
        </div>    
    @endauth

    
</body>
</html>
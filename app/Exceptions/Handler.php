use Illuminate\Auth\AuthenticationException;

protected function unauthenticated($request, AuthenticationException $exception)
{
    return response()->json([
        'status' => 'error',
        'message' => 'Unauthenticated'
    ], 401);
}

<?php

namespace App\Http\Requests\Auth\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'login_id' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function authenticate(): void
    {
        // adminガードでlogin_idとpasswordを使って認証
        // 成功したらセッションにユーザー情報（Adminオブジェクト）が保存される
        if (! Auth::guard('admin')->attempt($this->only('login_id', 'password'))) {
            // 失敗した場合はlogin_idのバリデーションエラーとして処理
            throw ValidationException::withMessages([
                'login_id' => trans('auth.failed'),
            ]);
        }
    }
}

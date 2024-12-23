<?php

/**
 * Controller Comment
 *
 * @package App\Http\Controllers
 * @description Controller untuk mengelola komentar pada artikel
 */

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class CommentController
 * Mengelola operasi CRUD untuk komentar artikel
 *
 * @package App\Http\Controllers
 */
class CommentController extends Controller
{
    /**
     * Constructor
     * Menerapkan middleware auth kecuali untuk method index, show, dan loadNew
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'loadNew']);
    }

    /**
     * Menyimpan komentar baru
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     *
     * @bodyParam content string required Isi komentar
     * @bodyParam article_id integer required ID artikel yang dikomentari
     *
     * @response {
     *   "success": true,
     *   "comment": {
     *     "id": 1,
     *     "content": "Isi komentar",
     *     "created_at": "2 menit yang lalu",
     *     "user": {
     *       "name": "Nama User",
     *       "profile_photo": "path/to/photo.jpg",
     *       "id": 1
     *     },
     *     "user_id": 1,
     *     "can_delete": true
     *   }
     * }
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'content' => 'required|string',
                'article_id' => 'required|exists:articles,id'
            ]);

            $comment = Comment::create([
                'content' => $request->content,
                'article_id' => $request->article_id,
                'user_id' => Auth::id(),
            ]);

            // Load comment with user relationship
            $comment->load('user');

            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'user' => [
                        'name' => $comment->user->name,
                        'profile_photo' => $comment->user->profile_photo,
                        'id' => $comment->user->id
                    ],
                    'user_id' => Auth::id(),
                    'can_delete' => true
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan komentar'
            ], 500);
        }
    }

    /**
     * Menyimpan balasan komentar
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     *
     * @bodyParam content string required Isi balasan
     * @bodyParam parent_id integer required ID komentar yang dibalas
     * @bodyParam article_id integer required ID artikel
     *
     * @response {
     *   "success": true,
     *   "reply": {
     *     "id": 2,
     *     "content": "Isi balasan",
     *     "created_at": "baru saja",
     *     "user": {
     *       "name": "Nama User",
     *       "profile_photo": "path/to/photo.jpg",
     *       "id": 1
     *     },
     *     "user_id": 1,
     *     "can_delete": true
     *   }
     * }
     */
    public function reply(Request $request)
    {
        try {
            $request->validate([
                'content' => 'required|string',
                'parent_id' => 'required|exists:comments,id',
                'article_id' => 'required|exists:articles,id'
            ]);

            $reply = Comment::create([
                'content' => $request->content,
                'parent_id' => $request->parent_id,
                'article_id' => $request->article_id,
                'user_id' => Auth::id(),
            ]);

            $reply->load('user');

            return response()->json([
                'success' => true,
                'reply' => [
                    'id' => $reply->id,
                    'content' => $reply->content,
                    'created_at' => $reply->created_at->diffForHumans(),
                    'user' => [
                        'name' => $reply->user->name,
                        'profile_photo' => $reply->user->profile_photo,
                        'id' => $reply->user->id
                    ],
                    'user_id' => Auth::id(),
                    'can_delete' => true
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan balasan'
            ], 500);
        }
    }

    /**
     * Memuat komentar baru
     *
     * @param int $articleId ID artikel
     * @param int $lastId ID komentar terakhir yang dimuat
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     *
     * @response {
     *   "success": true,
     *   "comments": [
     *     {
     *       "id": 3,
     *       "content": "Komentar baru",
     *       "created_at": "1 menit yang lalu",
     *       "user": {
     *         "name": "Nama User",
     *         "avatar": "path/to/avatar.jpg"
     *       },
     *       "can_delete": true
     *     }
     *   ]
     * }
     */
    public function loadNew($articleId, $lastId)
    {
        try {
            $comments = Comment::where('article_id', $articleId)
                ->where('id', '>', $lastId)
                ->whereNull('parent_id')
                ->with('user')
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'comments' => $comments->map(function($comment) {
                    return [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'created_at' => $comment->created_at->diffForHumans(),
                        'user' => [
                            'name' => $comment->user->name,
                            'avatar' => $comment->user->avatar ?? asset('lib/default_media/default.jpg')
                        ],
                        'can_delete' => Auth::id() === $comment->user_id
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat komentar baru'
            ], 500);
        }
    }

    /**
     * Menghapus komentar
     *
     * @param int $id ID komentar
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     *
     * @response {
     *   "success": true,
     *   "message": "Komentar berhasil dihapus"
     * }
     */
    public function destroy($id)
    {
        try {
            $comment = Comment::findOrFail($id);

            // Cek apakah user berhak menghapus komentar
            if (Auth::id() !== $comment->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Hapus semua balasan jika ini adalah komentar utama
            if (!$comment->parent_id) {
                Comment::where('parent_id', $comment->id)->delete();
            }

            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error deleting comment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus komentar'
            ], 500);
        }
    }

    /**
     * Mengambil komentar artikel
     *
     * @param int $articleId ID artikel
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     *
     * @response {
     *   "success": true,
     *   "comments": [
     *     {
     *       "id": 1,
     *       "content": "Komentar utama",
     *       "created_at": "2 jam yang lalu",
     *       "user_id": 1,
     *       "user": {
     *         "name": "Nama User",
     *         "profile_photo": "path/to/photo.jpg",
     *         "id": 1
     *       },
     *       "replies": [
     *         {
     *           "id": 2,
     *           "content": "Balasan komentar",
     *           "created_at": "1 jam yang lalu",
     *           "user_id": 2,
     *           "user": {
     *             "name": "User Lain",
     *             "profile_photo": "path/to/photo.jpg",
     *             "id": 2
     *           }
     *         }
     *       ]
     *     }
     *   ]
     * }
     */
    public function getArticleComments($articleId)
    {
        try {
            $comments = Comment::where('article_id', $articleId)
                ->whereNull('parent_id')
                ->with(['user', 'replies.user'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'comments' => $comments->map(function($comment) {
                    return [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'created_at' => $comment->created_at->diffForHumans(),
                        'user_id' => $comment->user_id,
                        'user' => [
                            'name' => $comment->user->name,
                            'profile_photo' => $comment->user->profile_photo,
                            'id' => $comment->user->id
                        ],
                        'replies' => $comment->replies->map(function($reply) {
                            return [
                                'id' => $reply->id,
                                'content' => $reply->content,
                                'created_at' => $reply->created_at->diffForHumans(),
                                'user_id' => $reply->user_id,
                                'user' => [
                                    'name' => $reply->user->name,
                                    'profile_photo' => $reply->user->profile_photo,
                                    'id' => $reply->user->id
                                ]
                            ];
                        })
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat komentar'
            ], 500);
        }
    }

    /**
     * Mendapatkan jumlah komentar artikel
     *
     * @param int $articleId ID artikel
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     *
     * @response {
     *   "success": true,
     *   "total": 10
     * }
     */
    public function getCommentCount($articleId)
    {
        try {
            $totalComments = Comment::where('article_id', $articleId)
                ->count();

            return response()->json([
                'success' => true,
                'total' => $totalComments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan jumlah komentar'
            ], 500);
        }
    }
}

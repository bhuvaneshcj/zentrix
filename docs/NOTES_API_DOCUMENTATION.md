# 📘 Notes API Documentation

Welcome to the Notes API.

This API allows users to:

-   Register and login
-   Create, update, delete notes
-   Soft delete and restore notes
-   Permanently delete notes
-   Manage categories
-   Manage tags
-   Attach tags to notes
-   Search and filter notes

This documentation is written for developers who are new to working with
APIs.

------------------------------------------------------------------------

# 🌐 Base URL

All endpoints start with:

http://your-domain.com/api

Example (Local Development):

http://127.0.0.1:8000/api

------------------------------------------------------------------------

# 🔐 Authentication

This API uses **Bearer Token Authentication (Laravel Sanctum)**.

After login or registration, you will receive a token.

You must include this token in all protected requests.

## How to Send Token

Add this header in every authenticated request:

Authorization: Bearer YOUR_TOKEN_HERE

Example:

Authorization: Bearer 1\|abc123xyz

------------------------------------------------------------------------

# 📦 Standard Response Format

## Success Response

{ "success": true, "data": { ... } }

## Error Response

{ "success": false, "data": { "message": "Error message", "errors":
{...} } }

------------------------------------------------------------------------

# 👤 AUTH MODULE

## 1️⃣ Register User

POST /register

Request Body (JSON):

{ "name": "Demo User", "email": "demo@example.com", "password": "123456"
}

Returns authentication token.

------------------------------------------------------------------------

## 2️⃣ Login

POST /login

{ "email": "demo@example.com", "password": "123456" }

Returns authentication token.

------------------------------------------------------------------------

## 3️⃣ Logout

POST /logout

Requires Authorization header.

------------------------------------------------------------------------

# 📝 NOTES MODULE

All endpoints below require authentication.

------------------------------------------------------------------------

## 1️⃣ List Notes

GET /notes

Optional query parameters:

?search=keyword\
?is_archived=1\
?is_pinned=1\
?is_favorite=1

Returns paginated results (default: 10 per page).

------------------------------------------------------------------------

## 2️⃣ Create Note

POST /notes

{ "title": "Laravel Notes", "content": "Building API module",
"category_id": 1, "tags": \[1,2\] }

Fields:

-   title: string (optional)
-   content: string (optional)
-   category_id: integer (must exist)
-   tags: array of tag IDs

------------------------------------------------------------------------

## 3️⃣ Get Single Note

GET /notes/{id}

------------------------------------------------------------------------

## 4️⃣ Update Note

PUT /notes/{id}

{ "title": "Updated Title", "content": "Updated content" }

------------------------------------------------------------------------

## 5️⃣ Soft Delete Note

DELETE /notes/{id}

Moves note to trash.

------------------------------------------------------------------------

## 6️⃣ Restore Note

POST /notes/{id}/restore

Restores a soft-deleted note.

------------------------------------------------------------------------

## 7️⃣ Permanently Delete Note

DELETE /notes/{id}/force-delete

Note must be in trash before permanent deletion.

------------------------------------------------------------------------

# 📁 CATEGORY MODULE

## 1️⃣ List Categories

GET /note-categories

## 2️⃣ Create Category

POST /note-categories

{ "name": "Work" }

## 3️⃣ Update Category

PUT /note-categories/{id}

## 4️⃣ Delete Category

DELETE /note-categories/{id}

------------------------------------------------------------------------

# 🏷 TAG MODULE

## 1️⃣ List Tags

GET /note-tags

## 2️⃣ Create Tag

POST /note-tags

{ "name": "Laravel" }

## 3️⃣ Update Tag

PUT /note-tags/{id}

## 4️⃣ Delete Tag

DELETE /note-tags/{id}

------------------------------------------------------------------------

# 🗑 Soft Delete Explanation

This system uses soft delete for notes:

-   Deleted notes are moved to trash
-   They can be restored
-   Permanent deletion must be done manually

------------------------------------------------------------------------

# 📌 Pagination

The /notes endpoint returns:

-   current_page
-   total
-   per_page
-   last_page

------------------------------------------------------------------------

# ⚠ Common Errors

401 -- Unauthorized (Missing/Invalid Token)\
404 -- Resource Not Found\
422 -- Validation Error\
500 -- Server Error

------------------------------------------------------------------------

# 🚀 Summary

This API supports:

-   User authentication
-   Notes management
-   Category management
-   Tag management
-   Tag assignment
-   Soft delete lifecycle
-   Filtering & search
-   Pagination

You are now ready to integrate the Notes API into your frontend
application.

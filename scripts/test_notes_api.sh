#!/bin/bash

# ==============================
# CONFIG
# ==============================

BASE_URL="http://127.0.0.1:8000/api"
EMAIL="demo$(date +%s)@test.com"
PASSWORD="password"
TOKEN=""

echo "=============================="
echo " Register User"
echo "=============================="

REGISTER_RESPONSE=$(curl -s -X POST "$BASE_URL/register" \
-H "Content-Type: application/json" \
-d "{
  \"name\": \"Demo\",
  \"email\": \"$EMAIL\",
  \"password\": \"$PASSWORD\"
}")

echo "$REGISTER_RESPONSE"

TOKEN=$(echo $REGISTER_RESPONSE | grep -o '"token":"[^"]*' | cut -d'"' -f4)

echo "TOKEN: $TOKEN"

echo "=============================="
echo " Create Categories"
echo "=============================="

CATEGORY1=$(curl -s -X POST "$BASE_URL/note-categories" \
-H "Authorization: Bearer $TOKEN" \
-H "Content-Type: application/json" \
-d '{"name": "Work"}')

echo "$CATEGORY1"

CATEGORY2=$(curl -s -X POST "$BASE_URL/note-categories" \
-H "Authorization: Bearer $TOKEN" \
-H "Content-Type: application/json" \
-d '{"name": "Personal"}')

echo "$CATEGORY2"

echo "=============================="
echo " Create Tags"
echo "=============================="

TAG1=$(curl -s -X POST "$BASE_URL/note-tags" \
-H "Authorization: Bearer $TOKEN" \
-H "Content-Type: application/json" \
-d '{"name": "Laravel"}')

echo "$TAG1"

TAG2=$(curl -s -X POST "$BASE_URL/note-tags" \
-H "Authorization: Bearer $TOKEN" \
-H "Content-Type: application/json" \
-d '{"name": "API"}')

echo "$TAG2"

TAG1_ID=$(echo $TAG1 | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)
TAG2_ID=$(echo $TAG2 | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)

echo "=============================="
echo " Create Note"
echo "=============================="

NOTE_RESPONSE=$(curl -s -X POST "$BASE_URL/notes" \
-H "Authorization: Bearer $TOKEN" \
-H "Content-Type: application/json" \
-d "{
  \"title\": \"Laravel Notes\",
  \"content\": \"Building full notes API module\",
  \"tags\": [$TAG1_ID, $TAG2_ID]
}")

echo "$NOTE_RESPONSE"

NOTE_ID=$(echo $NOTE_RESPONSE | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)

echo "NOTE ID: $NOTE_ID"

echo "=============================="
echo " List Notes"
echo "=============================="

curl -s -X GET "$BASE_URL/notes?search=Laravel" \
-H "Authorization: Bearer $TOKEN"

echo ""
echo "=============================="
echo " Update Note"
echo "=============================="

curl -s -X PUT "$BASE_URL/notes/$NOTE_ID" \
-H "Authorization: Bearer $TOKEN" \
-H "Content-Type: application/json" \
-d '{
  "title": "Updated Laravel Notes",
  "content": "Updated content for testing"
}'

echo ""
echo "=============================="
echo " Soft Delete Note"
echo "=============================="

curl -s -X DELETE "$BASE_URL/notes/$NOTE_ID" \
-H "Authorization: Bearer $TOKEN"

echo ""
echo "=============================="
echo " Restore Note"
echo "=============================="

curl -s -X POST "$BASE_URL/notes/$NOTE_ID/restore" \
-H "Authorization: Bearer $TOKEN"

echo ""
echo "=============================="
echo " Force Delete Note"
echo "=============================="

curl -s -X DELETE "$BASE_URL/notes/$NOTE_ID/force-delete" \
-H "Authorization: Bearer $TOKEN"

echo ""
echo "=============================="
echo " Logout"
echo "=============================="

curl -s -X POST "$BASE_URL/logout" \
-H "Authorization: Bearer $TOKEN"

echo ""
echo "=============================="
echo " Done Testing"
echo "=============================="
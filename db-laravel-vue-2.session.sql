SELECT * FROM users;

-- SELECT * FROM sessions;

-- SELECT * FROM personal_access_tokens;

SELECT * FROM notes;


SELECT users.id, email, title, content, categories FROM users INNER JOIN notes ON users.id = notes.user_id;


-- DELETE FROM sessions;

-- DELETE FROM personal_access_tokens;

-- DELETE FROM notes;
ALTER TABLE resume 
ADD COLUMN IF NOT EXISTS user_id INTEGER;

-- Step 2: Assign existing resume(s) to user(s)
-- This assigns the first resume to the first user
UPDATE resume 
SET user_id = 1
WHERE id = 1 AND user_id IS NULL;

-- If you have more resumes, assign them to other users:
-- UPDATE resume SET user_id = 2 WHERE id = 2 AND user_id IS NULL;
-- UPDATE resume SET user_id = 3 WHERE id = 3 AND user_id IS NULL;

-- Step 3: Make user_id NOT NULL (after assigning values)
ALTER TABLE resume 
ALTER COLUMN user_id SET NOT NULL;

-- Step 4: Add foreign key constraint
ALTER TABLE resume 
ADD FOREIGN KEY (user_id) 
REFERENCES users(id) 
ON DELETE CASCADE;

-- Step 5: Add unique constraint (one resume per user)
ALTER TABLE resume 
ADD UNIQUE (user_id);

-- Step 6: Create index for user_id lookups
CREATE INDEX IF NOT EXISTS idx_resume_user_id ON resume(user_id);

-- Step 7: Create index for updated_at (for "most recent resume" queries)
CREATE INDEX IF NOT EXISTS idx_resume_updated_at ON resume(updated_at DESC);

-- Step 8: Add comment for documentation
COMMENT ON COLUMN resume.user_id IS 'Foreign key to users table - each user has one resume';

-- ========================================
-- VERIFICATION
-- ========================================

SELECT setval(pg_get_serial_sequence('resume', 'id'), COALESCE(MAX(id), 0) + 1, false)
FROM resume;


SELECT '✓ Multi-user migration completed successfully!' AS status;

-- Show updated resume table structure
SELECT 
    column_name, 
    data_type, 
    character_maximum_length,
    is_nullable,
    column_default
FROM information_schema.columns 
WHERE table_name = 'resume' 
ORDER BY ordinal_position;

-- Show all constraints on resume table
SELECT
    tc.constraint_name,
    tc.constraint_type,
    kcu.column_name
FROM information_schema.table_constraints tc
JOIN information_schema.key_column_usage kcu 
    ON tc.constraint_name = kcu.constraint_name
WHERE tc.table_name = 'resume'
ORDER BY tc.constraint_type, tc.constraint_name;

-- Show current resume-user relationships
SELECT 
    r.id as resume_id,
    r.full_name,
    r.nickname,
    r.user_id,
    u.username,
    u.email,
    r.updated_at
FROM resume r
LEFT JOIN users u ON r.user_id = u.id
ORDER BY r.updated_at DESC;

-- Show all users (to see who can create resumes)
SELECT 
    id,
    sr_code,
    username,
    email
FROM users
ORDER BY id;
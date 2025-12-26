-- ========================================
-- PostgreSQL Update Migration Script
-- This preserves existing data while updating schema
-- ========================================

-- Step 1: Add new columns with default values
ALTER TABLE resume 
ADD COLUMN IF NOT EXISTS phones JSONB DEFAULT '[]'::jsonb,
ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Step 2: Migrate existing phone data to phones array
-- This converts single phone TEXT to JSONB array
UPDATE resume 
SET phones = jsonb_build_array(phone)
WHERE phone IS NOT NULL 
  AND phone != ''
  AND (phones IS NULL OR phones = '[]'::jsonb);

-- Step 3: Change address column from TEXT to JSONB
-- First, backup the existing address data
ALTER TABLE resume ADD COLUMN IF NOT EXISTS address_backup TEXT;
UPDATE resume SET address_backup = address WHERE address IS NOT NULL;

-- Parse existing address strings into structured format
-- This is a best-effort conversion - adjust the parsing logic based on your data format
DO $$
DECLARE
    r RECORD;
    addr_parts TEXT[];
    addr_json JSONB;
BEGIN
    FOR r IN SELECT id, address FROM resume WHERE address IS NOT NULL AND address != '' LOOP
        -- Split address by comma
        addr_parts := string_to_array(r.address, ',');
        
        -- Build JSON object (adjust indices based on your address format)
        -- Example: "Purok 7, Bolbok, Batangas City, Philippines"
        addr_json := jsonb_build_object(
            'house', COALESCE(trim(addr_parts[1]), ''),
            'barangay', COALESCE(trim(addr_parts[2]), ''),
            'city', COALESCE(trim(addr_parts[3]), ''),
            'province', COALESCE(trim(addr_parts[3]), ''),  -- Adjust as needed
            'zip', '',
            'country', COALESCE(trim(addr_parts[4]), 'Philippines')
        );
        
        -- Update the record
        UPDATE resume SET address = addr_json::TEXT WHERE id = r.id;
    END LOOP;
END $$;

-- Step 4: Convert address column to JSONB type
ALTER TABLE resume 
ALTER COLUMN address TYPE JSONB USING 
    CASE 
        WHEN address IS NULL OR address = '' THEN '{}'::jsonb
        WHEN address::text ~ '^[\[{]' THEN address::jsonb  -- Already JSON
        ELSE jsonb_build_object(
            'house', '',
            'barangay', '',
            'city', '',
            'province', '',
            'zip', '',
            'country', address
        )
    END;

-- Step 5: Set proper column types and constraints
ALTER TABLE resume 
ALTER COLUMN full_name TYPE VARCHAR(100),
ALTER COLUMN nickname TYPE VARCHAR(50),
ALTER COLUMN title TYPE VARCHAR(100),
ALTER COLUMN university TYPE VARCHAR(200),
ALTER COLUMN email TYPE VARCHAR(100);

-- Step 6: Drop the old phone column (after data migration)
ALTER TABLE resume DROP COLUMN IF EXISTS phone;

-- Step 7: Ensure personal_info has correct structure
-- Update any records with old date format
UPDATE resume 
SET personal_info = jsonb_set(
    personal_info,
    '{Date of Birth}',
    to_jsonb(
        CASE 
            WHEN personal_info->>'Date of Birth' ~ '^\d{4}-\d{2}-\d{2}$' 
            THEN personal_info->>'Date of Birth'
            ELSE to_char(
                to_date(personal_info->>'Date of Birth', 'Month DD, YYYY'),
                'YYYY-MM-DD'
            )
        END
    )
)
WHERE personal_info ? 'Date of Birth' 
  AND personal_info->>'Date of Birth' IS NOT NULL
  AND personal_info->>'Date of Birth' !~ '^\d{4}-\d{2}-\d{2}$';

-- Step 8: Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_resume_email ON resume(email);
CREATE INDEX IF NOT EXISTS idx_users_sr_code ON users(sr_code);
CREATE INDEX IF NOT EXISTS idx_users_username ON users(username);
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);

-- Step 9: Add updated_at triggers
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS update_users_updated_at ON users;
CREATE TRIGGER update_users_updated_at
    BEFORE UPDATE ON users
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

DROP TRIGGER IF EXISTS update_resume_updated_at ON resume;
CREATE TRIGGER update_resume_updated_at
    BEFORE UPDATE ON resume
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

-- Step 10: Add comments
COMMENT ON COLUMN resume.phones IS 'JSON array of phone numbers';
COMMENT ON COLUMN resume.address IS 'JSON object with structured address components';

-- Verification
SELECT 'Migration completed successfully!' AS status;

-- Show updated schema
SELECT 
    column_name, 
    data_type, 
    character_maximum_length,
    is_nullable
FROM information_schema.columns 
WHERE table_name = 'resume' 
ORDER BY ordinal_position;

-- Show sample data to verify
SELECT 
    id,
    full_name,
    phones,
    address,
    personal_info->>'Date of Birth' as date_of_birth
FROM resume 
LIMIT 5;

SELECT *
FROM users;


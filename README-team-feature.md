# Team Members Feature

This feature allows you to showcase developers, teachers, and other team members on your website with their profiles, roles, and experience.

## Installation

1. **Database Update**

   Run the following SQL queries to create the necessary table in your database:

   ```sql
   CREATE TABLE IF NOT EXISTS team_members (
       id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(100) NOT NULL,
       role VARCHAR(100) NOT NULL,
       experience TEXT,
       photo VARCHAR(255),
       display_order INT DEFAULT 0
   );
   ```

   If you already have the `team_members` table but with different column names:

   ```sql
   ALTER TABLE team_members 
   MODIFY role VARCHAR(100) NOT NULL,
   CHANGE bio experience TEXT,
   CHANGE image_path photo VARCHAR(255),
   MODIFY display_order INT DEFAULT 0;
   ```

2. **File Installation**

   - `admin/team-members.php`: Admin page to manage team members
   - `team.php`: Frontend page to display team members
   - Updates to `includes/header.php`: Added navigation links to the team page

3. **Directory Setup**

   Make sure the uploads directory exists and is writable:

   ```
   mkdir -p uploads/team_members
   chmod 755 uploads/team_members
   ```

## Usage

### Admin

1. Log in as an administrator
2. Navigate to the admin panel
3. Click on "Team Members" in the sidebar (you may need to add this link manually)
4. Add new team members with their:
   - Name
   - Role (Developer, Teacher, Administrator, etc.)
   - Experience/bio
   - Profile photo (optional)
   - Display order (lower numbers appear first)

### Frontend

The team page is accessible to all users at `team.php`, showing team members grouped by their role.

## Customization

- Profile photos are automatically resized and displayed in a circular format
- Team members are grouped by role: Developers, Teachers, and Others
- Each member card displays their name, role, and experience
- Missing profile photos are replaced with initials
- All styling uses the purple theme consistent with your site design

## Troubleshooting

- If photos don't upload, check that the `/uploads/team_members/` directory exists and has proper write permissions
- If the admin page doesn't show up in the sidebar, add a link manually to your admin sidebar file 
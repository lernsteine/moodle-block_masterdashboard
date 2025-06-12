
Master Dashboard (Block)
===========================

Overview:
---------
Master Dashboard is a clean, responsive block for the Moodle dashboard.
It displays each learner's course completion progress using color-coded cards:
- Red = Overdue
- Yellow = In Progress
- Green = Completed

Features:
---------
- Displays all enrolled courses with course completion enabled
- Automatically sorts into: Overdue, In Progress, Completed
- Responsive card layout (1–3 per row depending on screen size)
- Course image, title, and due/completion date shown clearly
- Colored borders for quick status recognition
- Fully mobile-friendly, supports all major themes
- Compatible with Moodle 4.0 – 5.x

Target Users:
-------------
- Learners (My Dashboard)
- Trainers (especially with read-only roles)
- Organizations with deadline-oriented learning

Technical Information:
----------------------
- Plugin Type: Block
- Directory: block_masterdashboard
- No external dependencies
- Language file included (en)
- Uses course overview files for thumbnails

Capabilities:
-------------
- block/masterdashboard:addinstance
- block/masterdashboard:myaddinstance

Installation:
-------------
1. Upload to /blocks/masterdashboard/
2. Log in as admin to complete the installation
3. Add block to user dashboards

Languages:
----------
- English included
- Additional translations can be added under /lang/

Screenshot:
-----------
Include a screenshot with 3-column layout and color-coded progress cards (optional but recommended).

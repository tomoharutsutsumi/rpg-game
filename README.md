# rpg-game

First of all, set up tables like this in Phpmyadmin

UserCredential table
<img width="858" alt="Screen Shot 2024-11-19 at 6 28 16 AM" src="https://github.com/user-attachments/assets/2a2b387f-982f-465d-a979-976c7aee1306">

UserDetail table
<img width="986" alt="Screen Shot 2024-11-19 at 6 28 48 AM" src="https://github.com/user-attachments/assets/9aa47476-c5dd-4b18-828d-f41d022e6557">

JUST TESTING
Game Flow

    Summary
    1.	Players log in or create an account.
    2.	They create a character, choosing a name and starter item.
    3.	Players select a quest from the menu.
    4.	They battle a monster in the quest:
        o	Victory: Level up, story text, and progress.
        o	Defeat: Return to quest menu.
    5.	Players can repeat quests or create new characters.
    ________________________________________
    Login & User Creation
    1.	Players can either log in with an existing account or create a new user.
    2.	Username must be unique, and the system will validate this during registration.
    3.	User credentials (username, password) will be stored in the UserCredential table.
    4.	The UserDetail table will store additional information like the creation date.
    ________________________________________
    Character Creation
    1.	Players create a character after logging in.
    2.	Each character starts at Level 1.
    3.	Players choose:
        o	A unique name for their character.
        o	A starter item (e.g., sword, shield, staff, etc.).
    4.	Starter health is determined by the Level 1 and starter item using the Health table.
    5.	Once the character is created, it will be stored in the CharacterDetails table, linked to the player.
    ________________________________________
    Quests
    1.	Players will select a quest from a list of available quests, displayed from the Quest table.
    2.	Each quest has:
        o	A unique ID (QID).
        o	A location.
        o	A predefined reward in terms of level-ups upon completion.
    3.	After selecting a quest, a descriptive intro text will set the scene (e.g., "You arrive at a dark forest. Suddenly, a monster appears.").
    ________________________________________
    Battle Mechanics
    1.	When a player starts a quest, they will encounter a monster.
    2.	Monsters have:
        o	A level.
        o	An item.
        o	Health, calculated using their level and item from the Health table.
    3.	Battles are turn-based:
        o	The player attacks the monster, reducing its health.
        o	The monster retaliates, reducing the player’s health.
    4.	The battle ends when:
        o	The monster's health reaches 0 (victory).
        o	The player’s health reaches 0 (defeat).
    ________________________________________
    Victory Conditions
    1.	If the player defeats the monster:
        o	They complete the quest and receive a level-up reward.
        o	A concluding text describes their success and continues the story.
        o	Player level is updated in the CharacterDetails table.
    2.	If the player is defeated by the monster:
        o	They return to the quest selection menu.
        o	No level-up or progress is granted.
    ________________________________________
    Health Calculation
    1.	Character health depends on:
        o	Their level.
        o	The item they are equipped with (from the Health table).
    2.	Monster health is calculated in the same way:
        o	Based on the monster’s level and item.
    ________________________________________
    Database Tables Overview
    1.	UserCredential:
        o	Stores Username and Password for logging in.
    2.	UserDetail:
        o	Stores UID, DateCreated, and links the user to their player profile.
    3.	CharacterDetails:
        o	Stores CharacterID, UID (linking to user), Level, Health, and Item.
    4.	Health:
        o	Links Level and Item to calculate Health for both characters and monsters.
    5.	Quest:
        o	Stores QID, Location, and level-up rewards.
    ________________________________________

Responsibility Assignment List

    1. Tomo
        Responsibilities:
            -User Registration and Login System:
                o Create pages for user sign-up and login.
                o Ensure Username uniqueness is validated.
                o Integrate user creation and login with the database.
            -Database Initialization:
                o Ensure the database and all tables are properly set up for the project.
        Tables to Work With:
            -UserCredential
            -UserDetail
    ________________________________________
    2. Esteban
        Responsibilities:
            -Quest Selection Page:
                o Display a list of quests available for the player.
                o Allow players to select a quest and transition to the quest start.
            -Quest Details Display:
                o Show quest descriptions and settings based on the selected quest.
        Tables to Work With:
            -Quest
            -CharacterDetails (to fetch player details for quest logic)
    ________________________________________
    3. Abdu
        Responsibilities:
            -Admin Login System:
                o Create a login system specifically for admins.
        Quest Management for Admins:
            -Implement features for admins to:
                o View all quests.
                o Create new quests (insert into the Quest table).
        Tables to Work With:
            -UserCredential (for admin login)
            -Quest (for quest creation)
    ________________________________________
    4. Jose
        Responsibilities:
            -Character Creation Page:
                o Allow players to create a character by selecting:
                o A unique name.
                o A starter item.
                o Automatically set Level = 1.
                o Calculate and display the initial health based on the level and item.
            -Battle Mechanics:
                o Implement turn-based battle logic.
                o Calculate and update health values for both characters and monsters.
                o Determine victory or defeat conditions.
                o Update character level upon quest completion.
            -Battle Page:
                o Display battle progress, monster stats, and player stats.
        Tables to Work With:
            -CharacterDetails (for character stats and creation)
            -Health (to fetch health based on level and item)
            -Quest (to retrieve quest-related monster stats)
    ________________________________________
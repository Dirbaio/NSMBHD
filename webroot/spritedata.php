0;Crash sprite [Sprites 0 to 20 are Unused - 0 used in unused levels];7;This sprite makes the level crash.@It is used in the unused levels.@It tries to load the player actor like sm64ds sprite 0;0
1;Crash sprite;7;This sprite makes the level crash.;0
2;Crash sprite;7;This sprite makes the level crash.;0
3;Crash sprite;7;This sprite makes the level crash.;0
4;Crash sprite;7;This sprite makes the level crash.;0
5;Crash sprite;7;This sprite makes the level crash.;0
6;Crash sprite;7;This sprite makes the level crash.;0
7;Crash sprite;7;This sprite makes the level crash.;0
8;Crash sprite;7;This sprite makes the level crash.;0
9;Crash sprite;7;This sprite makes the level crash.;0
10;Nothing;7;Not all the 'nothing' sprites use the same Class ID@The 'nothing' sprites might try to do something.@But it would need some sort of hacking knowledge to find out what.;0
11;Nothing;7;;0
12;Nothing;7;;0
13;Nothing;7;;0
14;Nothing;7;;0
15;Nothing;7;;0
16;Nothing;7;;0
17;Nothing;7;;0
18;Nothing;7;;0
19;Nothing;7;;0
20;Nothing;7;;0
21;Mega Goomba;4;Input ID works with any event ID@It will work without a zone.@Can be activated from anywhere in the view@But the goomba will only walk up and grow once you get near it.@The music will stop playing once it grows. and the camera will zoom out/follow it;1
value;2-3;;Input ID;
22;Hammer Brother spawn point;13;;3
checkbox;10;1;Midway Point;
checkbox;9;1;Unknown 9;Probably Nothing
checkbox;11;1;Unknown 11;
23;Tube Piranha Plant facing Top;1;Needs to be placed on top of a pipe to work.@@Sound Set: 00, 01, 05, 06, 07, 09, 0A, 0C, 0D, 0E, 18, 19, 1A, 1B, 1C, 1D, 1E, 1F, 20;2
checkbox;11;1;Fire-spitting;
list;10;0=One,1=Two,2=Three,3=Six;No. of fireballs;Unused (used in nsmbw)
24;Tube Piranha Plant facing Bottom;1;Needs to be placed on top of a pipe to work.@@Sound Set: 00, 01, 05, 06, 07, 09, 0A, 0C, 0D, 0E, 18, 19, 1A, 1B, 1C, 1D, 1E, 1F, 20;2
checkbox;11;1;Fire-spitting;
list;10;0=One,1=Two,2=Three,3=Six;No. of fireballs;Unused (used in nsmbw)
25;Tube Piranha Plant facing Right;1;Needs to be placed on top of a pipe to work.@@Sound Set: 00, 01, 05, 06, 07, 09, 0A, 0C, 0D, 0E, 18, 19, 1A, 1B, 1C, 1D, 1E, 1F, 20;2
checkbox;11;1;Fire-spitting;
list;10;0=One,1=Two,2=Three,3=Six;No. of fireballs;Unused (used in nsmbw)
26;Tube Piranha Plant facing Left;1;Needs to be placed on top of a pipe to work.@@Sound Set: 00, 01, 05, 06, 07, 09, 0A, 0C, 0D, 0E, 18, 19, 1A, 1B, 1C, 1D, 1E, 1F, 20;2
checkbox;11;1;Fire-spitting;
list;10;0=One,1=Two,2=Three,3=Six;No. of fireballs;Unused (used in nsmbw)
27;Bullet Bill Launcher;18;Might have an option for spawning bullet bills that home in on you  - The Class ID for the homing bullet bill exists;3
value;10;;Height;
list;9;0=Both,1=Left,2=Right;Shoot direction;
value;8;;Unknown 8;
28;Bob-Omb;18;Uses sound set 8;1
list;4;0=Regular,2=Ready to explode;Spawn type;
29;Princess Peach;4;;0
30;Monty Tank boss;4;;1
value;2-3;;Input ID;
31;Cheep Cheep;15;Benjamin: I do remember other values existing, but I haven't tested them in a while, so I don't remember.;2
list;11;0=Swim, 1=Green (chases Mario),2=Jumping,3=Doesn't move;Type;
list;8;0=Faster,1=Slower;Speed;
32;End-of-Level Flag;1;;2
checkbox;4;1;Secret exit;
value;11;;Unknown 11;Most likely a mistake made by Nintendo. There is a flag way outside the view in the map. Unused
33;Springboard;2;;2
checkbox;10;1;Offset 1/2 block right;
checkbox;11;1;Giant;Mario jumps higher on it. Unused - Used in Unused Level 2
34;Red Coin Ring;3;3E 00 00 03 00 00 - Create a trail of Blue Coins for a short period of time?;3
value;2-3;;Output ID;Will activate IDs like any other switch
list;6;0=Timer with music,1=No timer no music;Timer mode;
checkbox;10;1;Shift 1/2 block right;
35;Giant Bowser/pot/Bowser Jr.;4;and bowser jr?;0
36;Thwomp;18;;0
37;Spiny;1;;2
list;9;0=Regular,3=Faster;Speed;Unused
list;11;0=Regular,1=Spiny Ball;Spawn Type;
38;Boo;19;Trying to replace a .bin (contains frames of 2D enemies) with 3D models will result in graphical glitches.;1
checkbox;11;1;Faster;
39;Boss Activator;4;I am going to add theory notes for this sprite. If anyone can disprove, or prove the data or theory please do so. Eventually, maybe we can get these figured out.@-The World ID I believe is which cut scene to play.@-The Unknown 10 somehow loaded Peach and Bowser. Slight animation but they did not disappear. They were waiting for something.@-The World 1 fight has a zone. The data is all zeros, and the zone is zero. In most sprites 8-9 is where this data is stored. I tried a zone 1 setting with no luck, but again the sprite could be waiting for something else. @@The option to make peach appear might be linked to 62 unused bowser;5
value;0-1;;Input ID;
value;2-3;;Output ID;
list;11;6=World 1,0=World 2,4=World 3,3=World 4,2=World 5,5=World 6,1=World 7,7=World 8;World ID?;
value;10;;Unknown;I changed this to a 1 and was able to load Bowser Jr and Peach only. Still had 6 at nybble 11. Unused
value;8-9;;Zone ID?;Not sure on this, but these usually have zones. This would be the right nybble if this is a setting.
40;Lakitu;1;This sprite spawns Lakitu once. Once it's killed, it will not respawn.@If you want it to respawn, use sprite Lakitu Spawner (183) instead.@If your Sprite Set doesn't have Bullet Bill/Bomb in it, the game will freeze.@;1
checkbox;9;1;Spawn from BG;If set, makes Lakitu come from the BG like in sprite 183. 
41;Bowser Battle Switch;3;;4
value;2-3;;Input ID;Waits for Bowsers death. Bowser outputs ID to this sprite. That enables bridge to be destroyed.
checkbox;6;1;Wait for Mario?;
list;10;0=One time use,1=Multiple use,2=Reset after use;Switch mode;
list;11;0=2 tiles below switch,1=1 tile below switch;Bridge destroy?;
42;Chain Chomp with Log;1;It is possible for nybble 11, value 2 uses a sprite set that needs to be selected. That could explain the crash. I haven't tried this with the original unused level 3 to confirm.@It might try and load a file that does not exist - try loading the debug mode after it crashes or try using this:@http://nsmbhd.net/?page=thread&id=396@;1
list;11;0=No Chain Chomp,1=With Chain Chomp,2=Crash;W/Chain Chomp;0 and 2 are Unused
43;Chain Chomp without log;1;;0
44;Unused Jumping Fire Snake? (not crash sprite?);17;Does not use Class ID 106 like the used fire snake;0
45;Invisible coin brick;1;Only works when it's on top of an air block.;4
value;7;;Unknown 7;
value;8;;Unknown 8;
value;9;;Unknown 9;
value;10;;Unknown 10;
46;Spiked Ball Activator;3;Activates a Spiked Ball when the green blocks pass over it.;1
value;11;;Spiked Ball ID;
47;Lakithunder;4;;1
value;2-3;;Input ID;
48;Bubbles From Tube;15;Setting both input IDs to zero will make the bubbles always run.;2
value;0-1;;Input ID 1;You can start or stop bubbles using this ID.
value;2-3;;Input ID 2;You can start or stop bubbles using this ID.
49;Upside Down Bubbles From Tube;15;Setting both input IDs to zero will make the bubbles always run.;2
value;0-1;;Input ID 1;You can start or stop bubbles using this ID.
value;2-3;;Input ID 2;You can start or stop bubbles using this ID.
50;Right Bubbles From Tube;15;Setting both input IDs to zero will make the bubbles always run.;2
value;1;;Input ID 1;You can start or stop bubbles using this ID.
value;2-3;;Input ID 2;You can start or stop bubbles using this ID.
51;Left Bubbles From Tube;15;Setting both input IDs to zero will make the bubbles always run.;2
value;0-1;;Input ID 1;You can start or stop bubbles using this ID.
value;2-3;;Input ID 2;You can start or stop bubbles using this ID.
52;Buzzy Beetle;14;;1
list;11;0=Normal,1=Roof type/Start rolling,2=Start as shell,3=Start upside-down shell;Type;
53;Dry Bones;18;;0
54;Fire Ball from lava (Podoboo);17;The fireball jumps from the lava up to the position of the sprite.;0
55;Bullet Bill;18;;3
list;11;0=No shooting,1=Straight at Mario,2=Position in Editor;Shooting Position;
value;8-9;;Zone ID;
checkbox;7;1;Unknown 7;stay within zone?
56;Rotating Fire Bar;17;;4
list;10;0=One bar,1=Two opposed bars;Bar style;
value;11;;Length in fireballs;
list;8;0=Counter-clockwise,1=Clockwise;Rotation direction;
value;9;;Speed;
57;Coin / Coin in bubble;1;;4
list;11;0=Regular,1=In Bubble,2=3 In Bubble,3=Hops up-left,4=Drops,5=Hops,6=Moves Right,7=Moves up-left,8=From block,11=Hops high up-left;Type;
list;9;0=Left/In Front,1=Left Down/Behind,2=Left /More Down,7=Left Down Slow,10=Right, 14=Up Right Fast;Direction/Behind Fence;
checkbox;6;1;Shift 1/2 block down;
checkbox;5;1;Shift 1/2 block right;
58;Bowser boss;4;After roaring it probably allows Mario to move after boss activator makes him stop.;2
value;0-1;;Output ID;Outputs ID when in lava. (switch waits for bowser to output this)
value;2-3;;Input ID;Falls and starts moving when activating (does not need boss activator)
59;Hammer Brother;1;;0
60;Nothing;7;;0
61;Nothing;7;;0
62;Unused Bowser boss;4;May not be technically unused - possibly spawned after bowser jr throws dry bowser into pot in final boss cutscene.@@Game crashes after roaring animation if used anywhere other than World 8 Final Castle Area 3.@@The fireballs seem to crash the level. It is possible that a sprite set needs to be selected in order to use this sprite.;0
63;Dry Bowser;18;;2
value;2-3;;Input ID;
value;0-1;;Output ID;
64;Whomp;18;;2
checkbox;10;1;Stay in place;Can move around and chase Mario when not checked
checkbox;11;1;Giant;
65;Cheep Skipper;4;;1
value;2-3;;Input ID;
66;P-Switch;3;;9
value;2-3;;Output ID;0=disable
list;8;0=Start ID activation,1=Stop ID activation;Ouput mode;Activation has to be started/enabled. Can toggle once started or ended by hitting the opposite sw. again.
value;0-1;;Input ID;0=Ignore input ID, other value will wait for ID
list;9;0=One time use,1=Toggle start/stop,2=Toggle input/output;Input mode;Timer mode automatically resets switch to opposite output mode. Or it can be reset by another switch. With no timer, a start/stop toggle will respawn when you leave then return to the screen.
list;6;0=Timer with music,1=No timer no music;Timer mode;When timed, switch starts in start or stop. While timed, it is opposite.
value;5;;Time out sw. delay;In seconds, Only delays multiple on/off hits
checkbox;4;1;Affected by gravity;
checkbox;11;1;Upside down;
checkbox;10;1;Shift 1/2 block right;
67;Unused Shark (No generator);15;This sprite makes a Shark enemy that goes left and right. Useful if you want to make a Sushi enemy without generators.;1
list;5;0=Back &  forth, 1=Appears right/front, 2=Doesn't appear, 3=Appears left/behind;Behaviour;
68;Vertical Moving Platform;2;;6
value;2-3;;Input ID;
checkbox;4;1;Use Input ID;
value;11;;Width;
value;9;;Tiles to move;
list;7;0=Up,1=Down;Start Direction;
value;5;;Speed;
69;Horizontal Moving Platform;2;;6
value;2-3;;Input ID;
checkbox;4;1;Use Input ID;
value;11;;Width;
value;9;;Tiles to move;
list;7;0=Right,1=Left;Start Direction;
value;5;;Speed;
70;Unused spinning log;2;;1
checkbox;11;1;Longer;Makes the platform longer.
71;Vertical Moving Stone Block;2;;5
value;0-1;;Input ID;
value;9;;Tiles to move;
value;11;1;Width;
value;5;;Speed;0 appears to be faster, 1 slower
list;7;0=Up,1=Down;Start direction;
72;Horizontal Moving Stone Block;2;;4
value;9;;Tiles to move;
value;11;1;Width;
value;5;;Speed;0 appears to be faster, 1 slower
list;7;0=Right,1=Left;Start direction;
73;Hanging Metal Platform;2;;1
list;11;0=A lot,1=Medium,2=A little;Tilt;
74;Falling rock platform;2;;0
75;See-saw platform;2;;0
76;Scale Platform;2;;6
value;0-1;;Input ID 1;
value;2-3;;Input ID 2;
checkbox;4;1;Use Input ID;
value;10;;Right side rope height;
value;11;;Dist. between platforms;
value;9;;Left side rope height;
77;Sinking Platform;2;;1
value;11;;Width;
78;Moving Wood Platform on touch;2;;6
value;2-3;;Input ID;
checkbox;9;1;Use input ID;
value;11;;Width;
value;6;;Distance;0 = Unlimited, 1 = 14 blocks, 2 to 15 = 16xValue blocks. Unused (check if unused in nsmbw)
list;7;0=Up,1=Down,2=Right,3=Left;Direction;
list;5;0=Fast,1=Slow,2=Slower;Speed;
79;3-Platform Automatic Stationary Spinner;2;;2
checkbox;7;1;Clockwise rotation;
list;5;0=Faster,1=Slower;Speed;
80;Vertical Platform Generator;2;;2
list;7;0=Up,1=Down;Direction;
list;4;0=Keep moving,1=Wait for Mario;Off screen pos.;Used in the MvsL level. This controls what the platform does while off screen, best I can tell. Position is affected, but speed stays the same.
81;Nothing;7;;0
82;Spinning Red Rectangle Platform;2;;4
value;2-3;;Input ID;
list;9;0=Always spinning,1=Spin then stop,2=Spin when stood on;Type;
list;7;0=Counter clockwise,1=Clockwise;Direction;
checkbox;6;1;Stick to line;If you want yo go right put Clockwise. If you want to go left put Counter clockwise.
83;Self-activating brick/block [Spawned when ? block is hit];12;Brick/Q Block that activates when spawned.@Work In progress.@@Fields in here were translated from single nibble flipping.@@Please contribute by figuring out what the different combinations of sprite data do. Post your findings here: http://board.dirbaio.net/thread.php?id=244@@Nibbles 6-7 with AA makes a dud block spawn something. weird.;8
checkbox;8;1;Item overlap when spawned;
list;11;0=Red Mushroom/Fireflower,1=Starman,2=Coin,3=Poof?,4=2 Coins?,5=Mega Mushroom,6=Red Mushroom,7=1-Up,8=Red Mushroom 2,9=Fireflower always,10=Vine,11=Blue Shell,12=Red Mushroom 3,13=Yellow Spring,14=Red Mushroom 4,15=No Item?, 15=Mini Mushroom;Item type?;
list;4;0=No modification,1=Throw item right,2=Dud block,9=Throw item left;Unknown 4;
checkbox;5;1;Unknown 5;
list;6;0=No,8=Yes;Start with Dud block;Change to checkbox with mask 8 when checkbox bug is fixed
list;7;0=No modification,15=Enable;Item go down;Change to checkbox with mask 15 when checkbox bug is fixed
list;9;0=No modification,1=Throw item right,4=Item falls/throw item if on solid,15=Item spawn like touchscreen;Unknown 9;
list;10;0=No modification,1=Dud block,2=Brick spawn item,3=Brick into dud block,4=Block dissapear item goes up,5=Block dissapear,6=? Block dissapear item go down,7=Block dissapear 2,8=Nibble value in sprite 31,9=Dud block 2,11=Dud block 3,12=10 coin brick + item,13=10 coin brick,14=static Coin + item going down,15=static Coin;Unknown 10;
84;Zoom;6;{Reggie's (NSMBwii Level Editor) Sprite Data seems to be convertible to NSMBds Editor's format and some values from NSMBwii also work in NSMBds - Try and use info from Reggie.} [the zoom sprite was not unused in NSMBwii!] [Hiccup];4
value;4-5;;Delay;
value;6-7;;Unknown 6-7;
signedvalue;8-9;;Zoom Left;
signedvalue;10-11;;Zoom Right;
85;Flip Fence Side (Unused duplicate);1;;0
86;Spinning Red Triangle Platform;2;;4
value;2-3;;Input ID;
list;9;0=Always spinning,1=Spin then stop,2=Spin when stood on;Type;
list;7;0=Counter clockwise, 1=Clockwise;Direction;
checkbox;6;1;Stick to line;If you want to go right put Clockwise. If you want to go left put Counter clockwise. Unused.
87;Nothing;7;;0
88;Brick With P-Switch;3;;8
value;2-3;;Output ID;0=disable
list;8;0=Start ID activation,1=Stop ID Activation;Output mode;Activation has to be started/enabled. Can toggle once started or ended by hitting the opposite sw. again
value;0-1;;Input ID;0=Ignore input ID, other value will wait for ID
list;9;0=One time use,1=Toggle start/stop,2=Toggle input/output;Input mode;Timer mode automatically resets switch to opposite output mode. Or it can be reset by another switch. With no timer, a start/stop toggle will respawn when you leave then return to the screen.
list;6;0=Timer with music,1=No timer no music;Timer mode;When timed, switch starts in start or stop. While timed, it is opposite.
value;5;;Time out sw. delay;In seconds, Only delays multiple on/off hits
checkbox;4;1;Affected by gravity;
checkbox;11;1;Upside down;
89;Snailicorn;16;;0
90;Wiggler;13;Nybbles 10 and 11 don't look right... Maybe the OP meant nybble 4?;2
checkbox;11;1;Bigger;Unused
checkbox;4;;Unknown 4;
91;Line Controlled Platform;2;;7
value;0-1;;Input ID 1;
value;2-3;;Input ID 2;
checkbox;6;1;Wait for Mario;Will only moved when stood on.
list;7;0=Right,1=Left;Start direction;
list;4;0=Ignore input ID,1=Start with 2nd input ID,15=Stop with 2nd input ID;Mode;
value;5;;Speed;0 = Does not move
value;11;;Unknown 11;Used in unused level 1 maybe time until fall?
92;Eel;15;This sprite will not spawn if none of the values are checked.;3
checkbox;7;1;Moves Right;
checkbox;10;1;Moves Left;Overrides move right setting
checkbox;11;1;Non-moving;Overrides other settings
93;Arrow Sign;8;;5
list;10;0=Left,1=Down/Left,2=Down,3=Down/Right,4=Right,5=Up/right,6=Up,7=Up/Left;Direction;
checkbox;11;1;Arrow flip 180°;
checkbox;7;1;Small;
checkbox;9;1;Shift 1/2 tile down;
checkbox;8;1;Shift 1/2 tile left;
94;Swooper;14;;1
checkbox;11;1;Bigger;
95;Spinning jump board;2;;2
value;8-9;;Height/speed?;
value;2-3;;Input ID;
96;Sea Weed;8;;0
97;Nothing;7;;0
98;Nothing;7;;0
99;Manual Moving Wheel;2;;3
value;0-1;;Input ID;
checkbox;4;1;Use input ID;
checkbox;6;1;Stick to line;
100;Nothing;7;;0
101;Nothing;7;;0
102;Small Spiked Ball;18;;6
value;2-3;;Input ID;
checkbox;10;1;Start going right;
list;11;0=Only move on slope,1=slow,2=mid,3=fast;Speed;
checkbox;8;1;Speed 2?;
checkbox;9;;Use input ID?;
value;6-7;;Spiked Ball ID;For use with Spiked Ball Activator (46)
103;Dorrie;1;If end path node is not set, Dorrie will keep going back and forth between last two nodes. Set end as value 1 in unknown 6.@@Using value 256 in unknown 3 will lower Dorrie's head. 512 will raise it again. Set this at each node you want a change or it will continue from last node setting.;5
value;0-1;;Input ID;Starts facing away from background and turns to actual direction when activated. If set to zero, Dorrie won't start until touched by Mario. Unused.
list;5;0=Right,1=Left,2=Towards background,3=Away from background;Direction;2 and 3 don't function properly. 0, 2 and 3 are Unused.
value;6;;Size;Maximum of 3. Anything above 0 is Unused.
value;4;;Path ID;
value;10-11;;Start Node;255=Appear Always, 0=Appear at level start, Anything else=Appear from midway
104;Tornado;20;;0
105;Whirlpool;15;;1
checkbox;11;1;Shrink Away When Stop;
106;Red Coin;1;These will activate from input or output ID. They can be activated without the red coin red via there ID. @@The group ID allows the coins to be counted to 8 in their own group. Using different group IDs will start count at 0 until all 8 of that group are collected.@;3
value;0-1;;Input ID 1;
value;2-3;;Input ID 2;
value;11;;Count to 8 group ID;
107;? Switch;3;Test unused, strange function Output IDs@@Nybble 8 controls on/off function. If you set the 107 to off and the target sprite is off, it will not work. You can use 107 off once the target is turned on once. @;9
value;2-3;;Output ID;0=disable
list;8;0=Start ID activation,1=Stop ID Activation;Output mode;Activation has to be started/enabled. Can toggle once started or ended by hitting the opposite sw. again.
value;0-1;;Input ID;0=Ignore input ID, other value will wait for ID
list;9;0=One time use,1=Toggle start/stop,2=Toggle input/output;Input mode;Timer mode automatically resets switch to opposite output mode. Or it can be reset by another switch. With no timer, a start/stop toggle will respawn when you leave then return to the screen.
list;6;0=Timed,1= Non Timed;Timer Mode;When timed, switch starts in start or stop. While timed, it is opposite.
value;5;;Time out sw. delay;In seconds, Only delays multiple on/off hits
list;4;0=Not affected,1=Affected,2=Unknown;Affected by gravity;
checkbox;11;1;Upside down;
checkbox;10;1;Shift 1/2 block right;
108;Red ! Switch;3;;9
value;2-3;;Output ID;0=disable
list;8;0=Start ID activation,1=Stop ID Activation;Output mode;Activation has to be started/enabled. Can toggle once started or ended by hitting the opposite sw. again.
value;0-1;;Input ID;0=Ignore input ID, other value will wait for ID
list;9;0=One time use,1=Toggle start/stop,2=Toggle input/output;Input mode;Timer mode automatically resets switch to opposite output mode. Or it can be reset by another switch. With no timer, a start/stop toggle will respawn when you leave then return to the screen.
list;6;0=Timer with music,1=No timer no music;Timer mode;When timed, switch starts in start or stop. While timed, it is opposite.
value;5;;Time out sw. delay;In seconds, Only delays multiple on/off hits
list;4;0=Not affected,1=Affected,2=Unknown;Affected by gravity;
checkbox;11;1;Upside down;
checkbox;10;1;Shift 1/2 block right;
109;Amp (Electric Ball);18;;1
list;10;0=None,1=1/2 block right,2=1/2 block down,3=1/2 block right and down;Shift;
110;Brick with Red ! switch [Unused];3;If I tested correctly the red ! switch should activate the unused red dotted coins...I am still not sure about this.;8
value;2-3;;Output ID;0=disable
list;8;0=Start ID activation,1=Stop ID Activation;Output mode;Activation has to be started/enabled. Can toggle once started or ended by hitting the opposite sw. again.
value;0-1;;Input ID;0=Ignore input ID, other value will wait for ID
list;9;0=One time use,1=Toggle start/stop,2=Toggle input/output;Input mode;Timer mode automatically resets switch to opposite output mode. Or it can be reset by another switch. With no timer, a start/stop toggle will respawn when you leave then return to the screen.
list;6;0=Timer with music,1=No timer no music;Timer mode;When timed, switch starts in start or stop. While timed, it is opposite.
value;5;;Time out sw. delay;In seconds, Only delays multiple on/off hits
list;4;0=Not affected,1=Affected,2=Unknown;Affected by gravity;
checkbox;11;1;Upside down;
111;Giant Pink Floating Log;2;;2
value;6;;Glitchy;Very glitchy. Do not use.
value;5;;Unknown 5;No visible change.
112;Nothing;7;;0
113;Cheep-Chomp;15;;0
114;Small flamethrower;18;This flamethrower will not recharge. If you want a recharging flamethrower, use 118.;1
list;11;0=Right,1=Left,2=Up,3=Down;Direction;
115;Giant Spiked Ball;18;;6
value;2-3;;Input ID;
checkbox;10;1;Start going right;
list;11;0=Non-moving,1=slow,2=mid,3=fast;Speed;
value;8;;Speed 2?;
checkbox;7;1;Non-Moving;
checkbox;9;1;Unknown 9;
116;Water Bug;15;;2
checkbox;11;1;Don't move;
checkbox;9;1;Unknown 9;
117;Red Flying ? Block;1;I don't think nybbles 5 and 7 are right....They need to be confirmed.@There might be one for affecting the chance of an item being spawned as some items are more likely to appear in some stages?;5
list;10;0=Start Entrance,1=Midway Entrance;Spawn at;
checkbox;9;1;Start flying right;
list;8;0=16 tile,1=Forward 8/back four,2=Stay in place;Loop type;
list;6;0=Fly off screen,1=Center of Loop,2=Edge of Loop;16 tile loop;
value;11;;Unknown 11;
118;Flamethrower;18;;1
list;11;0=Right,1=Left,2=Up,3=Down;Direction;
119;Pendulum Platform;2;Nybbles look like they are of on these. 4-5 should be together. 6-7 should be together.;3
value;5-6;;Speed;Default in-game is 83....I think 5 may be left,  right
value;7;;Start delay;These may be start delay so they are timed so Mario can get to the next. Old values 0=Bottom going right,1=Unknown,2=Unknown,3=Unknown,4=Right side,8=Bottom going left,9=Unknown,10=Unknown,12=Unknown,15=Left side
value;4;;Size;Default in-game is 3. Big sizes should not be used, especially since you can't use them.
120;Piranha Plant;1;Sound Set: 00, 01, 05, 06, 07, 09, 0A, 0C, 0D, 0E, 18, 19, 1A, 1B, 1C, 1D, 1E, 1F, 20;0
121;Nothing;7;;0
122;Giant Piranha Plant;1;Sound Set: 00, 01, 05, 06, 07, 09, 0A, 0C, 0D, 0E, 18, 19, 1A, 1B, 1C, 1D, 1E, 1F, 20;0
123;Fire Spitting Piranha Plant;1;Sound Set: 00, 01, 05, 06, 07, 09, 0A, 0C, 0D, 0E, 18, 19, 1A, 1B, 1C, 1D, 1E, 1F, 20;0
124;Giant Fire Spitting Piranha Plant [Unused - used in nsmbw];1;Can't be defeated by shells unlike all other piranha plants (?)@So maybe slightly incomplete@@Sound Set: 00, 01, 05, 06, 07, 09, 0A, 0C, 0D, 0E, 18, 19, 1A, 1B, 1C, 1D, 1E, 1F, 20;0
125;Nothing;7;;0
126;Draw Bridge Platform;2;The input id does not work.@Does it need a value like "Use input id"@Or is it one of those fake values people keep putting in?;7
value;1;;Input ID;
list;11;0=Up,1=Down;Stop position;
checkbox;8;1;Moving;
value;6;;Open time;Standard: 1
value;5;;Closed time;Standard: 1
list;7;0=1,1=2,2=3,3=4,4=5,5=6,6=7,7=8,8=9: Straight Up;Maximum Angle;
value;4;;Length of side;
127;Giant 4 Spinning Platforms;2;;1
list;7;0=Counterclockwise, 1=Clockwise;Direction;
128;Warp Zone Pipe Cannon;1;;1
value;4;;Destination world;
129;Boss Battle Key Location;4;;0
130;Jumping Cheep Cheep;15;;1
checkbox;10;1;Cheep-Skipper;
131;Midway Point  Vertical;1;;0
132;Midway Point;1;;0
133;Nothing;7;;0
134;Nothing;7;;0
135;Nothing;7;;0
136;Pokey;20;;1
value;10-11;2;Height;
137;Nothing;7;;0
138;Nothing;7;;0
139;Nothing;7;;0
140;Glitched boss key?;4;The sprite will "throw" to the right across 35 blocks in length a key. Grabbing it will crash the game. The sprite is spawned in-game after you defeat a boss. Might have to do something with sprite 129.;0
141;Swelling Ground;2;not sure if one or both files below are used in this sprite;3
value;2-3;;Input ID;
checkbox;4;1;Use Input ID;
list;5;0=Up,1=Down;Direction;
142;Tight Rope;2;If both length and height are signed, it will be diagonal.;2
value;4;;Length;
signedvalue;5;;Height;
143;Unused Rotating Spiked ? Block;1;;0
144;Rotating Spiked ? Block;1;;2
list;11;0=Coin,1=Mushroom/flower,2=1-up;Contents;
list;10;0=Clockwise, 1=Counterclockwise;Rotation;(Comment:Normally it's clockwise rotation.)
145;Unused Rotating Spiked ? Block;1;;0
146;Ground Pound Gate;2;;0
147;Bump from below Red Platform;2;;1
value;11;;Length;
148;Goomba;13;;2
list;11;0=Normal, 1=Spinning(Air), 2=Rising(Pipe-Generator);Spawn Type;Lets the Goomba spawn different. 1 is spawned by Mega Mario's ground-pounding and 2 is spawned by pipe generators.
value;9;;Unknown 9;
149;Koopa Troopa;13;;3
list;11;0=Green,1=Red,2=Blue,3=Blue stay on ledge;Color;
checkbox;10;1;In Shell;
value;9;;Unknown 9;
150;Koopa Paratroopa;13;;4
list;10;0=Straight line,1=Left-right,2=Up-down,3=Jumping;Direction;
list;11;0=Green,1=Red,2=Blue;Color;
list;8;0=Down/Left,1=Up/Right,2=Unknown;Starting direction;
list;9;0=Midway, 1=Start Bottom / Left;Starting position;
151;Nothing;7;;0
152;Event On/Off Switch Block;3;;1
value;2-3;;Output ID;0=disable
153;Nothing;7;;0
154;Nothing;7;;0
155;Warp Entrance;3;The warp to level can be activated to allow its use. It will start in the off position. Once activated, it will be able to be used. Choose Input ID 1 for On/Off function, or ID 2 for permanent ON position.@@If you warp to the same area, the sprite will dissapear automatically. ;9
value;0-1;;Input ID 1;Use this ID fo the warp to activate with the ID, and then deactivate after being used.
value;2-3;;Input ID 2;Use this ID to activate ID and wait for an "off" command to turn off.
value;9;1;Width in tiles;
value;10;1;Height in tiles;
value;6;;Destination area;Set to 0 when warping to the same area or the game will freeze when warping
value;11;;Dest. entrance;
list;7;0=Respawn between areas,1=When Climbing Vine,3=Respawn in same area,8=Water splash fade screen;Warp mode;Value 0 will respawn between areas, but is 1 time use within same area. Value 3 will respawn in same area. Value 8 gives water splash fading screen instead of Mario head fading screen.
checkbox;4;1;Below view entrance?;Always used outside and below view.
value;5;;Unknown 5;Was marked do no sound. Freezes when respawn between areas is enabled. Works if other nybble 7 value is selected.
156;Nothing;7;;0
157;Fire Bro;1;;0
158;Boomerang Bro;1;;0
159;Nothing;7;;0
160;Nothing;7;;0
161;Nothing;7;;0
162;Tilting Mushroom;2;;5
value;11;;Speed;
value;8;;Length;
value;9;;Tilt angle;
list;6;0=Doesn't,2=Does,6=Unknown;Bouncing;
value;7;;Stalk height;
163;Nothing;7;;0
164;Input Controller - "And" (If X AND Y, do Z);3;;7
value;2-3;;Output ID;This sprite works by allowing up to 4 input IDs to be required to activate a single output. All input IDs must be set to a relevant value. Do not leave any as '0' - if you have less than four, you can enter the same ID into several spaces.
list;11;0=Start ID activation,1=Stop ID activation;Output mode;This decides whether the output id caused by the input id is enabling or disabling
value;0-1;;Input ID 1;
value;4-5;;Input ID 2;
value;6-7;;Input ID 3;
value;8-9;;Input ID 4;
list;10;0=Stop ID activation,1=Start ID activation,2=Start opposite output;Input mode;This sprite recieves an Input ID. This selects how it will start when an input ID is received.
165;Input Controller - "Or" (If X OR Y, do Z);3;;7
value;2-3;;Output ID;This sprite allows up to 4 inputs to trigger the same output ID. All input IDs must be set to relevant values. Do not leave any IDs as 0 - if you have less than four, you can enter the same ID into several spaces.
list;11;0=Start ID activation,1=Stop ID activation;Output mode;This is how the sprite will act  once in the zone.
value;0-1;;Input ID 1;
value;4-5;;Input ID 2;
value;6-7;;Input ID 3;
value;8-9;;Input ID 4;
list;10;0=Stop ID activation,1=Start ID activation,2=Start opposite output;Input mode;This sprite recieves an Input ID. This selects how it will start when an input ID is received.
166;Random output controller (If X, do Y OR Z) [Buggy];3;Why is it buggy?@You do not have to use all four Random ID slots, and you can use the same ID on multiple slots. Example: A in ID1, A in ID2, B in ID3. 2/3 chance of A, 1/3 chance of B(This sprite doesn't operate correctly);7
value;2-3;;Input ID;This event causes one of the four random IDs to trigger. Random ID is Activated or Deactivated based on this value
list;11;0=Stop ID activation,1=Start ID activation;Input mode;If the ID hasn't been activated by any sprite, value 1 will wait until it is activated once
value;0-1;;Random output  ID 1;
value;4-5;;Random output  ID 2;
value;6-7;;Random output  ID 3;
value;8-9;;Random output ID 4;
list;10;0=Start ID activation,1=Stop ID activation,2=Start opposite input;Output mode;This is how the sprite will act  once in the zone.
167;Timed input to output activator;3;This sprite does not count through IDs like the 268 does. It only uses the input ID to activate the output ID. From there you can have the output activate when the input is activated, and then output again the opposite on/off after the timer. Or you can set it so the output will not activate at the same time as the input, it waits the set time, and then activates the output . I guess this is used for sprites like the 168 where they ran out of nybbles to set a time function. Or for when you want to activate two sprites by only hitting one switch...Or simply to delay an ID from being sent out.;7
value;2-3;;Output ID;0=disable
value;0-1;;Input ID;0=disable
list;10;0=One time use,1=Multiple use,2=Reset after use;Switch Mode;
list;7;0=Input activates Output ID,1=Disable;Input activation;Don't get this either??? Why create a sprite and then disable it???
list;6;0=Input/Timer/output,1=In+Out/ Timer/ Out;Input/Output Mode;
checkbox;11;1;Invert Timed Mode;I don't understand why this is necessary. You can just change the timed mode to do exactly the same thing.
value;4-5;;Sec. between IDs;
168;Zone Activation Switch;3;-Set Enemy and/or Mario zone to 255 to ignore zone (can be triggered anywhere in level). @-Setting Enemy zone means all enemies in zone must be killed to trigger 168.@-Mario type only allows with selected types, but other rules still apply.@-Using an output ID of 62 will make blue coins that appear where Mario goes.@-A good way to test this sprite or any other non visible activator is to create a 197 block with the same ID. Enter the 168 zone and see if the block gets created when you want it to.;8
value;2-3;;Output ID;0=disable
list;11;0=Start output ID,1=Stop output ID;Inside Zone;This is how the sprite will act  once in the zone.
value;0-1;;Input ID;0=disable
list;10;0=Stop ID,1=Start ID,2=Toggle stop/start;Outside Zone;This sprite recieves an Input ID. This selects how it will start when an input ID is received. 0 will be default as an ID already recieved.
list;8;0=Any Mario,1=Small Mario,2=Super Mario,3=Fire Mario,4=Mega Mario,5=Mini Mario,6=Blue Shell Mario,7=Starman;Mario type;
list;9;0=Entering zone,1=Touching ground,2=In air only;Mario zone activation;This sets how switch is triggered.
value;6-7;;Enemy Kill Zone ID;255 to ignore the status of this zone
value;4-5;;Mario Zone ID;255 to ignore the status of this zone
169;Nothing?;1;;0
170;Nothing;7;;0
171;Nothing;7;;0
172;Nothing;7;;0
173;Rope;2;;3
value;11;;Length in tiles;
checkbox;5;1;Mario controls sway;
value;10;;Sway on own +/-;These values go from 0 (none) to F (almost horizontal). At a 4 tile (0 value length) they increase 1/2 tile at the bottom per number.
174;Moving Mushroom;2;;8
value;11;;Bouncing Speed;
value;8;;Width in blocks;
value;9;;Degree of tilting;
value;7;;Vertical offset;
checkbox;4;1;Falls when touched;
value;5;;Horizontal Speed;
value;6;;Unknown 6;
value;10;;Unknown 10;
175;Unused Bouncing Bricks;2;The texture inside puyo_lift.nsbtx is sampletex2.@It is probably a test sprite @@;1
list;4;0=Constant stretch, 1=Depress on step, 2=Solid;Behavior;Tested in DeSmuME 0.9.7, and NSMBe r209
176;Nothing;7;;0
177;Nothing;7;;0
178;Nothing;7;;0
179;Nothing;7;;0
180;Koopa on Fence;1;;4
checkbox;10;1;Walk Vertically;
list;11;0=Green,1=Red;Color;
checkbox;8;1;Walk Faster;
checkbox;9;1;Start on back of fence;
181;Nothing;7;;0
182;Nothing;7;;0
183;Lakitu Spawner;1;This sprite spawns Lakitu inside a Zone. If Lakitu is killed, it is respawned.;2
value;8-9;;Zone ID;
checkbox;11;1;Throw spinys backwards;Spiny appear behind lakitu when thrown.
184;Nothing;7;;0
185;Random Cheep-Cheep Generator;15;Values 10 and 11 could be type of Cheep Cheep, or amount???;3
value;8-9;;Zone ID;Let spawn random generated Cheep-Cheep in the selected Zone.
checkbox;10;1;Unknown 10;
checkbox;11;1;Unknown 11;
186;Paragoomba;13;;0
187;Manual Control Platform;2;;4
checkbox;10;1;Auto Start;
list;11;0=Left Up Right,1=Left  Down Right,2=Left Up Right,3=Left Right;Arrows;
checkbox;8;1;Disappear after a while;
list;6;0=When entering level at beginning,1=When entering level at midpoint;When to spawn;
188;Nothing;7;;0
189;Pipe cannon;1;If I remember correctly, there was a field that let you adjust the color. @Needs investigation;1
list;11;0=Far right,1=Far left,2=Medium right,3=Medium left,4=Straight up,5=Fast far right;Angle;
190;Nothing;7;;0
191;Hanging Bouncing ? Block;1;;2
list;11;0=Coin,1=Powerup,2=Star,3=1-up,4=Powerup,5=Mini Mushroom,6=Shell Powerup,7=Mega Mushroom,8=Mushroom;Item;
value;6;;Drop Length;
192;Falling coin;1;Strangely, putting too much at once at the screen will cause the game to lag a little when the switch is hit.;1
value;0-1;;Input ID;Dissapears after the switch time has run out.
193;Giant Dry Bones;18;;0
194;Giant Thwomp;18;;0
195;0 Stick to bottom length activator left;6;The DS screen is 12 tiles high. When this is used as a top limit, it will control the tiles below the icon. As a bottom limit, it controls tiles above.;3
list;4;0=Bottom limit,1=Top limit;Type;
value;10-11;;Pix. past limit;Pixels able to be seen past limit. This will shift 12 tile screen down from icon position.
checkbox;8;1;Death below limit;Must be empty map block, no tiles below. Mario image must fall out of screen.
196;0 Stick to bottom length activator right;6;The DS screen is 12 tiles high. When this is used as a top limit, it will control the tiles below the icon. As a bottom limit, it controls tiles above. This sprite is used to center the screen.;3
list;4;0=Bottom limit,1=Top limit;Type;
value;10-11;;Pix. past for limit;Pixels able to be seen past limit. This will shift 12 tile screen down from icon position.
checkbox;8;1;Death below limit;Must be empty map block, no tiles below. Mario image must fall out of screen.
197;Tile creator/destroyer;3;;8
value;0-1;;Input ID 1;0=disable
value;2-3;;Input ID 2;0=disable
list;9;0=Off/Destroy first,1=On/Create first;Type;
list;8;0=Red blocks,1=Bricks,2=Blue coins,3=Stone blocks,4=Wood blocks,6=Right facing red pipe top edge,7=Broken red pipe top right edge,15=Used ? block;Block Type;
list;4;0=Multiple use,1=Permanent;Input activation;
list;7;0=Solid,1=Checkerboard,2=Reverse Checkerboard;Pattern;
value;10;;Width;
value;11;;Height;
198;In air vertical scroll stop left;6;;4
list;4;0=Bottom limit,1=Top limit;Type;
value;10-11;;Visible Y pix. past;Pixels able to be seen past limit.
checkbox;8;1;Allow disable;Can only be set to a max of 31 pixels. Past that move the sprite.
value;9;;Unknown;
199;In air vertical scroll stop right;6;;4
list;4;0=Bottom limit,1=Top limit;Type;
value;10-11;;Visible Y pix. past;Pixels able to be seen past limit.
checkbox;8;1;Allow disable;
value;9;;Unknown;
200;Nothing;7;;0
201;Nothing;7;;0
202;Nothing;7;;0
203;Purple tilting mushroom;2;;0
204;Jumping Fire Snake;20;;1
value;11;;Length;Fire snake length extends from sprite position to right
205;Flame Chomp;17;;1
value;8-9;;Zone ID;
206;Moving Sloped Ghost House Goo (16x3);2;;0
207;Giant Cheep Cheep;15;;1
checkbox;11;1;Green (follows Mario);
208;Nothing;7;;0
209;Giant Hammer Bro;1;Can destroy brick/wood/stone blocks.;0
210;Vs. Battle Star;1;;1
value;10-11;;Star ID;These must be the same number of stars as in the original level. Each star must have a unique ID, starting from 1. If not, the game will hang.
211;Blooper;15;;2
value;2-3;;Input ID;Appears when event is triggerd
checkbox;11;1;Avoid Mario;Goes away from Mario
212;Blooper Nanny;15;Never releases mini bloopers.;0
213;Blooper, Mini-Blooper Spawn;15;;1
checkbox;11;1;Avoid Mario;
214;Nothing;7;;0
215;Nothing;7;;0
216;Nothing;7;;0
217;Nothing;7;;0
218;Auto Scroll Start;6;We have yet to find out how to get it to work.@@-----Mariosunshine's discovery------@This sprite have to use together with paths. Unknown 1 is for speed. It should be divisible by 8. Unknown 3 still unknown, but if it's zero. It won't move.@@And I've found some interesting formula: x=98k/y. Where k is a amount of pixel being passed within x mario's second. And y is the value you entered in unknown 1. It relates together. But it might not always work. It may have something to do with unknown 3 too...@@Calculation example: let say you want a sprite to move camera up 4 tiles within 16 mario sec.. Your formula is x=98k/y. As 4 tiles=64 pixels. Substitute it, then you have to multiply it with 98. 98x64=6272. And subtitute x with 16. Then 16=6272/y.@And solve it to find y. After you got y, you insert that number into unknown 1. And test it in the emu, does 4 tiles are passed within 16 mario sec. or not? But I think it does. Remember unknown 3 ≠ 0. And sometimes the time don't have to be whole number. @That's all I've found. :)@Hope this help!@;4
value;5;;Start-Node;
value;10-11;;Path ID;
value;8-9;;Unknown 8-9;
value;6-7;;Unknown 6-7;
219;Spiny Beetle;14;;2
list;10;0=Up,1=Left,2=Down,3=Right;Facing;
checkbox;11;1;Move left;
220;Bowser Jr. boss;4;;3
list;10;0=Throws shells,1=Hops in shell after damage,2=Hops in shell after damage,3=Throws shells;Behavior;
list;9;0=slow slow med,1=slow med med,2=slow med fast;Speed progression;
list;11;0=Unknown Option A,1=Unknown Option B,2=Unknown Option C,3=Unknown Option D;Unknown 11;Check if it is camera thing
221;Nothing;7;;0
222;Mini Goomba;13;Jumps with 'wah' even though it is used in any view with the 'wah' in the music. Very probably based on the normal goomba.;2
checkbox;10;1;Shift 1/2 block right;
checkbox;11;1;Shift 1/2 block down;
223;Flip Fence Side;1;;0
224;Large Flip Fence Side;1;;0
225;Nothing;7;;0
226;Hanging Scuttle Bug;14;;1
value;10;;Drop height;
227;Money Bag;1;;0
228;Roulette Block;1;;1
list;11;0=Shell,1=Mini mushroom;Extra item;
229;Petey Piranha;4;;1
value;2;;Unknown 2;This value is used in the boss in the castle.
230;Nothing;7;;0
231;Water;8;Nybbles 4 and 5 have to do with the auto rise/fall waves. Not sure of what the values do yet.@@;9
value;0-1;;Input ID 1;On ID 1 the water will rise then lower.
value;2-3;;Input ID 2;On ID 2 the water will rise, but have to be activated again with a stop command to lower.
value;11;;Speed to rise;
value;8-9;;Tiles to Rise;
checkbox;6;1;Visible;
value;7;;Opacity;0 means it's invisible.
value;5;;Rise/fall;
value;4;;Unknown 4;
value;10;;Unknown 10;
232;Hanging ? Block;1;;6
value;0-1;;Input ID 1;
value;2-3;;Input ID 2;
list;11;0=Coin,1=Fire Flower,2=Star,3=1 up,4=Fire Flower,5=Mini Mushroom,6=Blue Shell,7=Mega Mushroom,8=Red Mushroom,9=Red Mushroom;Contents;
value;10;;2nd powerup list? 10;
value;5;1;Unknown 5;
checkbox;4;;Multi use;
233;Swinging Pole on Line;2;;3
checkbox;10;1;Moving;
value;11;;Pole length;
checkbox;9;1;Start moving left;
234;Lava;8;Without either input ID, the lava will rise automatically.@@If you set a value for either one of the IDs by themselves, the lava will rise to the height you select when that ID is activated.@@If you set a values for both IDs, ID1 will make the lava go up then down. ID2 will only make the lava rise, and will wait for an off command from the ID2 activator again.@@Nybbles 6 and 7 don't seem to have an effect. But they are used ingame.@@Nybbles 4 and 5 could still do something.;6
value;0-1;;Input ID 1;
value;2-3;;Input ID 2;
signedvalue;10-11;;Speed to rise;If positive rises, if negative lowers.
value;8-9;;Tiles to Rise;
value;6;;Unknown 6;Can't see a change for nybble 6.
value;7;;Unknown 7;Can't see a change for nybble 7.
235;Star Coin;3;;6
value;0-1;;Output ID;Output ID will activate another sprite
list;9;0=Star Coin-1,1=Star Coin-2,2=Star Coin-3;Number;
list;6;0=Timer with music,1=No timer no music;Timer when ID set;Timer works just like switches.
checkbox;10;;1/2 tile right;
checkbox;11;;1/2 tile down;
checkbox;8;;Behind Fence;
236;Spinning Square Platform;2;;6
value;2-3;;Input ID;Appears/Dissapears when ID is Activated/Deactivated
list;9;0=Always spinning,1=Spin then stop,2=Spin when stood on;Type;If set to 2 it will rotate depending on which side mario is standing on.
checkbox;7;1;Clockwise rotation;
checkbox;4;1;Shift 1/2 block down;
checkbox;5;1;Increase rotation speed/occurrences;
checkbox;6;1;Stick to line;If you want yo go right put Clockwise. If you want to go left put Counter clockwise.
237;Broozer;19;;0
238;Purple Tilt Mushroom;2;;2
value;10;;Width;
value;11;;Unknown 11;
239;Rising/Lowering Mushroom;2;;4
value;10;;Dist. to go down;
value;11;;Width;
checkbox;8;1;Return to original pos;
value;9;;Distance to go up;
240;Nothing;7;;0
241;Rotating Bullet Bill Cannon;1;First checkboxes are bottom cannons.;3
value;6-7;;Height;
binary;10-11;;Cannons to flip;individual bits indicate flipped cannons
binary;8-9;;Empty Spots;individual bits indicate empty spots
242;Expand/Contract Mushroom;2;;3
checkbox;10;1;Start Expanded;
value;11;;Stalk Height;
checkbox;9;1;Small;
243;Roof Spiny;14;;0
244;Bouncing Mushroom;2;;4
list;10;0=None,1=1/2 block left,2=1/2 block right;Shift;
value;11;;Stalk Height;
value;8;;Unknown 8;
value;1;;Unknown 9;
245;Swelling Tube;1;;6
value;11;;Dest. Entrance;
value;6;;Destination Area;
value;8;;Pump Y Distance from tube;
value;10;;Pump X Distance from tube;
checkbox;7;1;Unknown 7;Has something to do with moving pump to other side of tube
checkbox;9;1;Unknown 9;Has something to do with moving pump to other side of tube
246;Floating Small Barrel;1;;1
checkbox;11;1;Unknown 11;
247;Shark Generator;15;;1
value;8-9;;Zone ID;
248;Balloon Boo;19;When using an input ID, the area below Balloon Boo hurts Mario. You can't see anything there, but the game sees it as part of the sprite. When you activate the input ID, the area goes away, and Balloon Boo returns to normal.;4
value;0-1;;Input ID 1;
value;2-3;;Input ID 2;
value;8-9;;Zone ID;
checkbox;7;1;Stay within zone;
249;Wall Jump Platform;2;;4
value;11;;Width;
value;8;;Height;
checkbox;6;1;Wait for Mario;
value;5;;Speed;
250;Crow;19;;0
251;Giant Eel;15;;3
checkbox;10;1;Visible facing left;
checkbox;7;1;Visible facing right;
value;8-9;1;Zone ID;Required, or he will not move.
252;Banzai Bill cannon;1;;1
list;9;0=Both, 1=Left Only, 2=Right Only;Shooting Direction;1 and 2 are Unused
253;Nothing;7;;0
254;Kab-omb;1;;1
checkbox;11;1;Chase When Angry;This makes him chase Mario if hit by a fireball/Raining Debris. If not set, he will run left and right.
255;Junglekusa (Unused Jungle Image);8;The height of the Sprite is 4 blocks. It is counted by the block where it is placed downwards. The image is repeated sideways.@@Isn't scroll up/down suppose to be a signed value?;2
checkbox;4-5;;Swimmable;This can't be a two nybble value...and it needs a value for the checkboxArea under Image is swimable, too.
signedvalue;10-11;;Scroll Up/Down;If swimmable, the image doesn't move. If negative value it scrolls down, if positive value it scrolls up.
256;Rotating Carry-Through-Wall Platform;2;;2
list;7;0=Left,1=Right;Start side;
checkbox;10;1;Unknown 10;
257;Cheep-Cheep with coin trail;15;;0
258;Unused Spike bass;15;This Spike Bass does not require a water height.;1
value;8-9;;Zone ID;
259;Poison Water;8;This will not rise automatically like lava if you don't have an input ID set.@@ID 1 will rise to set height, then auto return to normal.@ID 2 will rise to height and wait for Input ID with a "stop" setting to lower.@@Nybbles 6 and 7 don't seem to have an effect. But they are used ingame.@@Nybbles 4 and 5 could still do something.;6
value;0-1;;Input ID 1;On ID 1 the posion water will rise then lower.
value;2-3;;Input ID 2;On ID 2 the posion water will rise, but have to be activated again with a stop command to lower.
value;10-11;;Speed;
value;8-9;;Tiles to Rise;
value;6;;Unknown 6;
value;7;;Unknown 7;
260;Fast Giant Down Shooting Spike;18;Requires sound set 09 for the noise. (HEX);5
list;4;0=Normal,1=Fast movement,2=Slow movement,3=No movement at all;Behaviour;
list;5;0=Start to fall,2=Leaving from bottom,8=Start with delay;How to start;
value;6;;speed and distance;
value;7-8;;Fall delay;
value;10-11;;Leave delay;
261;Fast Giant Up Shooting Spike;18;Requires sound set 09 for the noise. (HEX);5
list;4;0=Normal,1=Fast movement,2=Slow movement,3=No movement at all;Behaviour;
list;5;0=Start to fall,2=Leaving from bottom,8=Start with delay;How to start;
value;6;;speed and distance;
value;7-8;;Fall delay;
value;10-11;;Leave delay;
262;Fast Giant Left Shooting Spike;18;Requires sound set 09 for the noise. (HEX);5
list;4;0=Normal,1=Fast movement,2=Slow movement,3=No movement at all;Behaviour;
list;5;0=Start to fall,2=Leaving from bottom,8=Start with delay;How to start;
value;6;;speed and distance;
value;7-8;;Fall delay;
value;10-11;;Leave delay;
263;Fast Giant Right Shooting Spike;18;Requires sound set 09 for the noise. (HEX);5
list;4;0=Normal,1=Fast movement,2=Slow movement,3=No movement at all;Behaviour;
list;5;0=Start to fall,2=Leaving from bottom,8=Start with delay;How to start;
value;6;;speed and distance;
value;7-8;;Fall delay;
value;10-11;;Leave delay;
264;Increase chance of drops on Mega-Mario groundpound;1;Only increase the CHANCE to drop Goombas when Groundpounding.;1
value;8-9;;Zone ID;
265;Ghost House Pointing Hands;19;;1
value;11;;Pointing hands  ID;
266;Invisible Fireball;17;Is an invisible fireball that will follow you if near, or fly a straight path from where it was placed.@Probably spawned by Fire Chomp;0
267;Nothing;7;;0
268;Underwater Bounce Ball;15;Has half tile shifting nybbles@If it is ground pounded it will be destroyed like a goomba when ground pounded.;0
269;Giant Wiggler;13;There's a path ID value for this sprite. However, the path ID is 0, and the path ID is different for the Dorrie, auto-scrolling, and "Block Train" (Moving Green Blocks) sprite and it may have a different Path ID. Investigate.;0
270;Smashed pipe sprite;12;The effect will activate every time you get to the point were you can see the sprite.@This sprite gets spawned in-game every time you break a pipe as mega Mario. This is only the case for a certain data.@@NOTE: There was sprite data controlling the size, color and behavior. Must investigate.@@http://board.dirbaio.net/thread.php?id=353 Please check here.;3
value;10-11;;Length;
value;6;;Color/type?;
value;7;;Facing?;0-9 destroy?
271;Crow Generator;19;;1
value;6-7;;Zone ID;
272;Falling Snow From Tree;16;;1
checkbox;11;1;Branch Left;
273;Snowball Thrower;16;;0
274;Sinking Snow;16;Length of 0 makes the game crash!;1
value;11;;Length;Game will crash if set to 0!
275;Blockhopper;20;;2
value;11;;Height;
list;9;0=Nothing,1=Coin,2=Powerup,3=1-up,5=Star,11=Mega-Mushroom;Item;Nothing, Star and Mega Mushroom Values are Unused
276;Scroll & Mario stop sideways;6;Remember, the DS screen is 16 tiles wide. Make sure you allow for the full width of the screen or you camera will scroll shaky. Using X coordinates, numerically these should be 15 tiles apart.@@This sprite, along with 198/199, doesn't always work. Try moving 276s up or down if they don't work.@@Sprite must be placed at the bottom of the limit. Height is counted from the sprite position up.@@When using retro scroll horizontal, you have to select "stop from scrolling left".@@These can be passed through using a connected pipe.@;3
list;4;0=None,1=Horizontal ,2=Vertical;Retro scroll;
value;9-10;1;Tile Height Up;You can calculate by map Y coordinates.
list;11;0=Stop from scrolling right,1=Stop from scrolling left;Direction;
277;Arrow;8;;2
list;4;0=Mario behind,1=Mario in front;Layer;
list;5;0=Up,1=Up/Right,2=Right,3=Down/Right,4=Down,5=Down/Left,6=Left,7=Up/Left;Direction;
278;Groundpound Ghost House Goo;2;;2
value;10;;Height;
value;11;;Width;
279;1-Way Door;2;;1
list;11;0=Faces up. opens from left,1=Faces up. opens from right,2=Faces down. opens from left,3=Faces down. opens from right,4=Faces right. opens from bottom,5=Faces right. opens from top,6=Faces left. opens from bottom,7=Faces left. opens from top;Type;Value 8+ is 2 tiles higher opens from left, and can't be touched by Mario.
280;Horizontal camera offset;6;You must set values for right and below tiles distances to activate. These are just like zones, the tiles are coordinates of activation. Setting either setting to zero will not give Mario an area to activate the sprite.@@The default game lag is 2 tiles. This is in addition to that lag. This will shift Mario's center to one side or the other.@@Camera will not return to normal until re-entering sprite zone. So far, at least until more data is found.;3
signedvalue;10-11;;Pixel offset +=left;Default value is 2 tiles (32 pixels) either way from center
value;8-9;;Pixels to full offset;This is how many pixels Mario needs to move past the sprite until it reaches the full horizontal offset.
value;6-7;;Pixel zone below;This is how many pixels below the sprite that the sprite can be activated in.
281;Squiggler;12;;1
list;11;0=Up, 1=Down;Pipe exit facing;
282;Vine;2;Requires BOTH sprite sets "Vine" and "Rope".@If not, it crashes.;3
value;10;;Swing angle;
value;11;;Length;
checkbox;5;1;Dont restrict angle;
283;Spike Bass;15;;3
value;8-9;;Zone ID;
checkbox;10;1;Move along line;
value;11;;Length;
284;Pumpkin;19;/enemy/kabochan.nsbmd;1
checkbox;11;1;Explode at start;Explode at start.
285;Falling Scuttle Bug;1;;1
value;8-9;;Zone ID;
286;ID Count Up activator;3;;4
value;2-3;;Output ID;0=disable
value;0-1;;Input ID;0=disable
list;10;0=One time use,1=Multiple use,2=Reset after use;Switch mode;
value;4-5;;Sec. between IDs;
287;Enemy-in-Pipe Generator;1;Uses sound set 8 for Bob-ombs;3
list;10;0=Goomba,1=Bob-omb;Enemy;
list;11;0=Up,1=Down,2=Left,3=Right;Direction;
checkbox;9;1;Slower Spawn Rate;Unused (From NSMBW)
288;Nothing;7;;0
289;Expandable Block;2;;1
checkbox;11;1;Turn solid at 4th block;Unused
290;Flying ? Block;3;;8
value;2-3;;Output ID;trigger another ID when switch selected
value;0-1;;Input ID;
checkbox;9;1;Start flying right;
list;8;0=16 tile,1=Forward 8/back 4,2=Stay in place;Loop type;
list;6;0=Fly off screen,1=Center of loop,2=Edge of loop;16 tile loop;
list;11;0=Coin,1=Shroom/Flower,2=Star,3=1-up,4=? Switch,5=P-Switch,6=! Switch,7=Vine with mario clone, 8=Mini Mushroom;Item;
checkbox;4;1;Connect with pointing hands;
value;7;;Pointing hands ID;pointing hands only
291;Brick Block with ? Switch;3;;8
value;2-3;;Output ID;0=disable
list;8;0=Start ID activation,1=Stop ID Activation;Output mode;Activation has to be started/enabled. Can toggle once started or ended by hitting the opposite sw. again.
value;0-1;;Input ID;0=Ignore input ID, other value will wait for ID
list;9;0=One time use,1=Toggle start/stop,2=Toggle input/output;Input mode;Timer mode automatically resets switch to opposite output mode. Or it can be reset by another switch. With no timer, a start/stop toggle will respawn when you leave then return to the screen.
list;6;0=Timer with music,1=No timer no music;Timer mode;When timed, switch starts in start or stop. While timed, it is opposite.
value;5;;Time out sw. delay;In seconds, Only delays multiple on/off hits
list;4;0=Not affected,1=Affected,2=Unknown;Affected by gravity;
checkbox;11;1;Upside down;
292;Event Activated Door;3;;3
value;2-3;;Input ID;
value;9;;Destination area;
value;10-11;;Dest. entrance;
293;Touching ground vertical scroll stop left ;6;This sprite seems to disable all other camera sprites when activated.@@It also changed horizontal scrolling...I think.;3
list;4;0=Bottom limit,1=Top limit;Type;
value;10-11;;Visible Y pix. past;Pixels able to be seen past limit.
value;8-9;;Disable Y pix. past;Can only be set to a max of 31 pixels. Past that move the sprite.
294;Touching ground vertical scroll stop right;6;This sprite seems to disable all other camera sprites when activated.;3
list;4;0=Bottom limit,1=Top limit;Type;
value;10-11;;Visible Y pix. past;Pixels able to be seen past limit.
value;8-9;;Disable Y pix. past;Can only be set to a max of 31 pixels. Past that move the sprite.
295;Mummy Pokey;4;;1
value;2-3;;Input ID;
296;Horizontal Moving Platform;2;Do not requires a sprite set.;4
value;4-5;;Width;
value;6-7;;Tiles to move;
list;9;0=Right,1=Left;Start direction;
list;11;0=Faster, 1=Slower;Speed;
297;Horizontal Moving Stone Block ;2;;7
value;6;;Use Input ID;
list;7;0=Right,1=Left;Start Direction;
value;11;1;Width;
value;8;;Height X 2;
value;9;;Tiles to move;
value;5;;Speed;
value;10;;Unknown;
298;Moving Stone Block w/ Spikes;2;;8
value;11;;Width;
value;10;;Height;
signedvalue;8;;Tiles to move;Negative numbers = down/right. Positive numbers up/left. NOT diagonal for example - ticking "Horizontal movement" and "Tiles to move" to minus -1 will make it move right one block. For vertical movement -8 is no move movement -7 is down one -6 is down two ect
value;9;;Speed;
value;6;;Start delay;
value;7;;End delay;
checkbox;4;1;Horizontal movement;
list;5;0=None,1=Top,2=Bottom,3=Top and Bottom,4=Right,5=Left,6=Right and left;Spikes;
299;Moving Green Blocks;2;It owns sudden mind to decimate unspecified substantial tiles amidst lures way. (It destroys solid tiles)@Probably because the sprite spawns the green block tiles in the main tile set.;5
value;10;;Path ID;If you don't have a matching ID, the game will freeze.
value;8;;Length;
list;9;0=Start of level,1=Midway entrance;Spawn location;
value;6-7;;Starting node;
value;4;;Allow fall;
300;Ghost House Elevator ;2;;1
value;11;;Y-Shift (pixels);
301;Toadsworth;4;What is the unused type? Well, it makes glitchy blocks that do nothing. Never use it.;1
list;11;0=1-Up,1=Unused,2=Item,3=Mega Mushroom,4=Background Chooser;Type;1 is glitchy.
302;Mushroom House Block;4;;3
value;11;;Block ID;
list;4;0=1-Up,1=Bottom Screen Background;Bonus game;Used in background chooser
checkbox;10;1;Unk. 10 Always marked;
303;Spinning Chain with Spike Ball;18;;2
value;10;;Nothing;
list;11;0=Right (Counter-Clockwise),1=Up (Counter-Clockwise),2=Left (Clockwise),3=Down (Clockwise);Starting Position;0 and 1 go counter-clockwise, 2 and 3 go clockwise
304;Giant Falling Spike;18;Requires sound set 09 for the noise. (HEX);6
list;4;0=Normal,1=Fast movement,2=Slow movement,3=No movement at all;Behaviour;
list;5;0=Start to fall,2=Leaving from bottom,8=Start with delay;How to start;
value;6-7;;Fall delay;Use "Fall frames" to add frames to delay.
value;8;;Fall frames;
value;9-10;;Leave delay;Use "Leave frames" to add frames to delay.
value;11;;Leave frames;
305;Final Castle Create Loop;1;;2
value;10-11;;ID;
value;8-9;;Width;Nybbles are reverse order
306;Final Castle Wrong Path;1;;2
value;11;;ID;
value;10;;Height;Sprite is positioned at the bottom
307;Giant Rising Spike;18;Requires sound set 09 for the noise. (HEX);6
list;4;0=Normal,1=Fast movement,2=Slow movement,3=No movement at all;Behaviour;
list;5;0=Start to fall,2=Leaving from bottom,8=Start with delay;How to start;
value;6-7;;Fall delay;Use "Fall frames" to add frames to delay.
value;8;;Fall frames;
value;9-10;;Leave delay;Use "Leave frames" to add frames to delay.
value;11;;Leave frames;
308;Giant Left Shooting Spike;18;Requires sound set 09 for the noise. (HEX);6
list;4;0=Normal,1=Fast movement,2=Slow movement,3=No movement at all;Behaviour;
list;5;0=Start to fall,2=Leaving from bottom,8=Start with delay;How to start;
value;6-7;;Fall delay;Use "Fall frames" to add frames to delay.
value;8;;Fall frames;
value;9-10;;Leave delay;Use "Leave frames" to add frames to delay.
value;11;;Leave frames;
309;Giant Right Shooting Spike;18;Requires sound set 09 for the noise. (HEX);6
list;4;0=Normal,1=Fast movement,2=Slow movement,3=No movement at all;Behaviour;
list;5;0=Start to fall,2=Leaving from bottom,8=Start with delay;How to start;
value;6-7;;Fall delay;Use "Fall frames" to add frames to delay.
value;8;;Fall frames;
value;9-10;;Leave delay;Use "Leave frames" to add frames to delay.
value;11;;Leave frames;
310;Fog FG effect;8;;7
value;6;;Opaque;0...15 where 15 is completely opaque
value;9;;Position;The higher the value, the higher the Texture position on the Y-axis
value;8;;Scrolling speed;Scrolling when the screen moves. (0 scrolls with BG)
list;4;0=Left-Down,1=Left-Up,2=Right-Down,3=Right-Up;Movement Angle;
value;10;;Horizontal Move;0...15 where 0 is the slowest
value;11;;Vertical Move;0...15 where 0 is the slowest
checkbox;5;;Unknown;Has something to do with the visibility of the FG effect?
311;Snow FG effect 1;8;;7
value;10;;H-Speed;
value;11;;V-Speed;Good
value;9;;Sway Stuff6;
value;8;;Sway Stuff5;
value;7;;Sway Stuff4;
value;6;;Sway Length;
value;5;;Sway Stuff2;
312;Rising Green Mushroom Platform;2;;6
value;2-3;;Input ID;
value;10;;Stem height/go down;
value;11;2;Width of top;
value;5-6;;Movement distance?;
checkbox;8;1;Allow to go down;
value;9;;Unknown 9;
313;Snow FG effect 2;8;;0
314;Snow FG effect 3;8;;0
315;Cloud FG effect;8;;0
316;Water FG effect 1;8;;0
317;Water FG effect 2;8;;0
318;Fire FG effect 1;8;;1
value;10-11;;Speed;
319;Fire FG effect 2;8;;1
value;10-11;;Speed;
320;Fire FG effect 3;8;;1
value;10-11;;Speed;
321;Light FG effect 1;8;;0
322;Light FG effect 2;8;;0
323;Soft Cloud Platform;2;Unused tile version in Sky tileset (World 7);1
value;11;;Length;Cloud extends from sprite position to the right.
324;Grassland Clouds FG effect? [Unused];8; it's sprite data is the same r to the other FG effects.;7
value;6;;Opaque;0...15 where 15 is completely opaque
value;9;;Position;The higher the value, the higher the Texture position on the Y-axis
value;8;;Scrolling speed;Scrolling when the screen moves. (0 scrolls with BG)
list;4;0=Left-Down,1=Left-Up,2=Right-Down,3=Right-Up;Movement Angle;
value;10;;Horizontal Move;0...15 where 0 is the slowest
value;11;;Vertical Move;0...15 where 0 is the slowest
checkbox;5;;Unknown;Has something to do with the visibility of the FG effect?
325;FG effect? [Unused];8;Needs investigation.@It's sprite data is the same to the other FG effects.@This is the Light beam ClassID that Dirbaio posted a while back...;7
value;6;;Opaque;0...15 where 15 is completely opaque
value;9;;Position;The higher the value, the higher the Texture position on the Y-axis
value;8;;Scrolling speed;Scrolling when the screen moves. (0 scrolls with BG)
list;4;0=Left-Down,1=Left-Up,2=Right-Down,3=Right-Up;Movement Angle;
value;10;;Horizontal Move;0...15 where 0 is the slowest
value;11;;Vertical Move;0...15 where 0 is the slowest
checkbox;5;;Unknow;Has something to do with the visibility of the FG effect?
end





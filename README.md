# Modular-Node.js-IRC-bot

An extremely modular IRC bot written in Node.js (VERY early in development at the moment)

## How to use

1. Rename settings.js.example to settings.js.
2. Change globalNick to the bot's name. Change username and realname.
3. Change the prefix for the bot.
4. Change 'address' to the irc server(s) you wish to connect to. Change 'channels' to the channel(s) you would like to connect to.
5. Install Node.js. Go in a command prompt and navigate to the bot's folder. Type: node main.js

Your bot should be on the desired channel.

## Modules
• globalServModules: server-level modules  
• globalModules: modules that are loaded in all channels  
•	"#channel": {  
		donotslap: {} <- these are where channel specific modules are loaded  
	},

### Modules list
(These all assume you are using the ! prefix.)

#### yesno
Usage: y/n  
Makes the bot say either yes or no.

#### chance
Usage: !chance  
Gives a random number.

#### tell
Usage: !tell username message  
This leaves a message for the target user. When the selected user says something, the notice is said.  
Example:  
User: !tell OtherUser hi  
OtherUser: Hi there!  
Bot: User: Tell OtherUser hi  

#### calc
This module requires calc from here: [Calc]  (http://sourceforge.net/projects/calc/files/calc/2.12.4.13/)  
Usage:   
These commands can calculate problems:  
!calc (2+2) - prints 4  
!calc print (2+2) - prints 4  

These commands can print text:  

!calc ("hi") - prints "hi"  
!calc print("hi") - prints hi  
!calc printf("hi") - prints hi  

#### chatbot
Usage: Say the bot's name. It will respond.  
This module uses Cleverbot, an artificial intelligence.  

#### seen
Usage: !seen username  
This shows when the last user was seen.  

#### ddg
This module requires python 2.7 to be installed on the server that it runs on.  

Usage: !ddg searchquery  
This is basically a mini-search engine.  

#### donotslap
(Bot needs chanop)  
User: /me slaps user around a bit with a large fishbot  
The bot will then kick you. >:)  

#### fire
Usage: !fire  
Bot: Help! My object is on fire! Please use the firehose!  
You: /me uses the firehose  
Bot: Thanks!  

#### flipcoin
Usage: !flipcoin  
Returns heads or tails.  

#### rolldice
Usage: !rolldice  
Returns a number between 1 and 6.  

#### time
Usage: !time  
Returns the date and time.  

#### truefalse
Usage: t/f  
Says true or false.  

#### welcome
Usage: No usage - just join the channel  
This module welcomes you when you join the channel.  

#### excuse
Usage: !excuse  
Returns a weird excuse.  

#### say
Usage: !say message  
Says the message that you say in the command.  

#### about
Usage: !about  
Returns information about the bot.  

#### cookie  
Usage: !cookie  
Gives the user a cookie with a beverage.

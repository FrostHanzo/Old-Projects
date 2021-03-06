import java.util.*;
/**
 *  This class is the main class of the "World of Zuul" application.
 *  "World of Zuul" is a very simple, text based adventure game.  Users
 *  can walk around some scenery. That's all. It should really be extended
 *  to make it more interesting!
 *
 *  To play this game, create an instance of this class and call the "play"
 *  method.
 *
 *  This main class creates and initialises all the others: it creates all
 *  rooms, creates the parser and starts the game.  It also evaluates and
 *  executes the commands that the parser returns.
 *
 * @author  Michael Kolling and David J. Barnes
 * @version 2008.03.30
 */

public class Game
{
    private Parser parser;
        private Room currentRoom;
private Room previousRoom;
private ArrayList<Item> carriedItems;
        private Stack roomHistory = new Stack();

    /**
     * Create the game and initialise its internal map.
     */
    public Game()
    {
carriedItems = new ArrayList<Item>();
        createRooms();
        parser = new Parser();
                                                                      }

    /**
     * Create all the rooms and link their exits together.
     */
    private void createRooms()
    {
                                Room outside, theatre, pub, lab, office, library, restroom;

        // create the rooms
        outside = new Room("outside the main entrance of the university");
        theatre = new Room("in a lecture theatre");
        pub = new Room("in the campus pub");
        lab = new Room("in a computing lab");

Item computer = new Item("computer", 1);
lab.addItem(computer);

Item server = new Item("server", 2);
lab.addItem(server);

        office = new Room("in the computing admin office");
library = new Room("In the University's Library");
restroom = new Room("In the Rest room");

        // initialise room exits
        outside.setExit("east", theatre);
        outside.setExit("south", lab);
        outside.setExit("west", pub);

        theatre.setExit("west", outside);

        pub.setExit("east", outside);

        lab.setExit("north", outside);
        lab.setExit("east", office);
lab.setExit("south", library);

        office.setExit("west", lab);

library.setExit("north", lab);
library.setExit("east", outside);
library.setExit("west", restroom);
        restroom.setExit("east", library);
        restroom.setExit("west", outside);

        currentRoom = outside;  // start game outside

                /**
                * Add some items to the CarriedItems list
                */
                carriedItems.add(new Item("shotgun", 4));
		        carriedItems.add(new Item("PocketPC", 1));
        carriedItems.add(new Item("cellphone", 1));
                         }

/**
* Print out what the player is carrying
*/
    private void printCarriedItems() {
        if (carriedItems.size() == 0) {
            System.out.println("you are not carrying anything");
        } else {
            System.out.print("You have the following:");
            for (int n = 0; n < carriedItems.size(); n++) {
                Item item = (Item) carriedItems.get(n);
                System.out.print(" " + item.getDescription());
            }
System.out.println();
       System.out.println("Total weight of all carried Items: " + totalWeight(carriedItems));
                    }
    }

    /**
     * Calculate the total Weight of all items in a list
     */
    private int totalWeight(ArrayList L) {
        int n=0;
        int sum = 0;
        while (n < L.size()) {
            Item i = (Item) L.get(n);
            sum += i.getWeight();
            n++;
        }
        return sum;    // not found above
    }

    /**
     *  Main play routine.  Loops until end of play.
     */
    public void play()
    {
        printWelcome();

        // Enter the main command loop.  Here we repeatedly read commands and
        // execute them until the game is over.

        boolean finished = false;
        while (! finished) {
            Command command = parser.getCommand();
            finished = processCommand(command);
        }
        System.out.println("Thank you for playing.  Good bye.");
    }

    /**
     * Print out the opening message for the player.
     */
    private void printWelcome()
    {
        System.out.println();
        System.out.println("Welcome to the World of Zuul!");
        System.out.println("World of Zuul is a new, incredibly boring adventure game.");
        System.out.println("Type 'help' if you need help.");
        System.out.println();
        System.out.println(currentRoom.getLongDescription());
    }

    /**
     * Given a command, process (that is: execute) the command.
     * @param command The command to be processed.
     * @return true If the command ends the game, false otherwise.
     */
    private boolean processCommand(Command command)
    {
        boolean wantToQuit = false;

        if(command.isUnknown()) {
            System.out.println("I don't know what you mean...");
            return false;
        }

        String commandWord = command.getCommandWord();
        if (commandWord.equals("help")) {
            printHelp();
        }
        else if (commandWord.equals("go")) {
                        goRoom(command);
        }
        else if (commandWord.equals("look")) {
            goRoom(command);
}
else if (commandWord.equals("take")) {
            take(command);
}
else if (commandWord.equals("drop")) {
            drop(command);
           		}
               else if (commandWord.equals("back")) {
        back(command);
	}
else if (commandWord.equals("items")) {
        printCarriedItems();
	}
        else if (commandWord.equals("quit")) {
            wantToQuit = quit(command);
        }
        // else command not recognised.
        return wantToQuit;
    }

    // implementations of user commands:

    /**
     * Print out some help information.
     * Here we print some stupid, cryptic message and a list of the
     * command words.
     */
    private void printHelp()
    {
        System.out.println("You are lost. You are alone. You wander");
        System.out.println("around at the university.");
        System.out.println();
        System.out.println("Your command words are:");
        parser.showCommands();
    }

    /**
     * Try to go to one direction. If there is an exit, enter the new
     * room, otherwise print an error message.
     */
    private void goRoom(Command command)
    {
        if(!command.hasSecondWord()) {
            // if there is no second word, we don't know where to go...
            System.out.println("Go where?");
            return;
        }

        String direction = command.getSecondWord();

        // Try to leave current room.
        Room nextRoom = currentRoom.getExit(direction);
if (nextRoom == null)
            System.out.println("There is no door!");
        else {
                        roomHistory.push(currentRoom);
			            enterRoom(nextRoom);
        }
    }

    /**
     * Enters the specified room and shows which room it is.
     */
    private void enterRoom(Room nextRoom) {
        currentRoom = nextRoom;
        System.out.println(currentRoom.getLongDescription());
    }

/**
     * implementation of back command. Should be revised and rewritten for better results.
     */
    private void back(Command command)
    {
            		        if(command.hasSecondWord()) {
		            System.out.println("Back where?");
		            return;
		        }
		        if (roomHistory.isEmpty())
		            System.out.println("You can't go back to nowhere!");
		        else {
		            Room previousRoom = (Room) roomHistory.pop();
		            enterRoom(previousRoom);
		        }
		    }

    /**
     * "Quit" was entered. Check the rest of the command to see
     * whether we really quit the game.
     * @return true, if this command quits the game, false otherwise.
     */
    private boolean quit(Command command)
    {
        if(command.hasSecondWord()) {
            System.out.println("Quit what?");
            return false;
        }
        else {
            return true;  // signal that we want to quit
        }
}

/**
* find Items by name
*/
private Item findByName(String s, ArrayList L) {
        int n=0;
        while (n < L.size()) {
            Item i = (Item) L.get(n);
            if (s.equals(i.getDescription()))
                return i;
            n++;
        }
        return null;
    }

/**
* Implement a drop command
*/
        public void drop(Command command) {
        if (! command.hasSecondWord()) {  // "DROP",but no object named
            System.out.println("Drop what?");
            return;
        }
        String s = command.getSecondWord();
        Item i = findByName(s, carriedItems);
        if (i == null) {
            System.out.println("You don't have a " + s);
            return;
        }
        carriedItems.remove(i);
        currentRoom.addItem(i);
        System.out.println("You have dropped the " + s);
    }

/**
* Implement a Take command
*/
public void take(Command command) {
        if (! command.hasSecondWord()) {  // "TAKE",but no object named
            System.out.println("Take what?");
            return;
        }
        String s = command.getSecondWord();
        Item i = findByName(s, currentRoom.getItems());
        if (i == null) {
            System.out.println("There is no " + s + " in this room ");
            return;
        }
currentRoom.getItems().remove(i);
        carriedItems.add(i);
               System.out.println("You have taken the " + s);
    }

public static void main(String[] args)
    {
        Game Game = new Game();
        Game.play();
    }
}
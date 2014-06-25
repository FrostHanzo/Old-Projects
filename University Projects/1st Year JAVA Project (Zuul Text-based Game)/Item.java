/**
 * Class for Items in Zuul With Main game.
 *
 * @Author: Georgi Zhivankin
 * @Version: 02/04/2009 1.1
 */
public class Item
{
	// description and weight of the item
	private String description;
	private int    weight;

	/**
	 * Constructor for objects of class Item
	 */
	public Item(String d)
	{
		description = d;
		weight = 0;
	}

	public Item(String d, int w)
	{
	    description = d;
	    weight = w;
	}

/**
	 * Accessor for description
	 *
	 */
	public String getDescription()
	{
		return description;
	}

	/**
	 * Accessor for weight
	 *
	 */
	public int getWeight()
	{
		return weight;
	}

/**
	* Do whatever happens when the player uses this item.
	     * Print messages telling the player what is happening.
	     * This version has no effect (override in subclasses).
	     *
	     * @param player    The player using the item.
	     */
	    public void use(Player player)
	    {
	        System.out.println("Nothing happens.");
	        System.out.println();
	    }

	    /**
	     * Pause for dramatic effect.
	     */
	    protected void dramaticPause()
	    {
	        try {
	            Thread.sleep(3500);
	        } catch (InterruptedException e) {}
	    }
   		}
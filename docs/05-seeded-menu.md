# Seeded Menu Data

The database seeder creates a complete fast food menu for development and testing.

## Categories & Products

### Burgers
| Product              | Price  | Prep Time |
|---------------------|--------|-----------|
| Classic Burger       | $5.99  | 5 min     |
| Double Cheeseburger  | $8.49  | 7 min     |
| Bacon BBQ Burger     | $9.99  | 8 min     |
| Veggie Burger        | $7.49  | 6 min     |
| Chicken Burger       | $7.99  | 6 min     |

**Available Customizations:**
| Customization     | Type   | Price    |
|------------------|--------|----------|
| No Onion         | remove | Free     |
| No Pickles       | remove | Free     |
| No Lettuce       | remove | Free     |
| Extra Cheese     | add    | +$1.00   |
| Extra Bacon      | add    | +$1.50   |
| Gluten-Free Bun  | add    | +$2.00   |

### Sides
| Product              | Price  | Prep Time |
|---------------------|--------|-----------|
| French Fries         | $3.49  | 4 min     |
| Onion Rings          | $4.49  | 5 min     |
| Chicken Nuggets 6pc  | $5.99  | 5 min     |
| Mozzarella Sticks    | $4.99  | 5 min     |
| Coleslaw             | $2.99  | 1 min     |

**Available Customizations:**
| Customization  | Type | Price    |
|---------------|------|----------|
| Large Size    | size | +$1.50   |
| Extra Sauce   | add  | +$0.50   |

### Drinks
| Product    | Price  | Prep Time |
|-----------|--------|-----------|
| Cola       | $2.49  | 1 min     |
| Lemonade   | $2.99  | 1 min     |
| Iced Tea   | $2.49  | 1 min     |
| Milkshake  | $4.99  | 3 min     |
| Water      | $1.49  | 1 min     |

**Available Customizations:**
| Customization | Type   | Price    |
|--------------|--------|----------|
| Large Size   | size   | +$1.00   |
| No Ice       | remove | Free     |

### Desserts
| Product          | Price  | Prep Time |
|-----------------|--------|-----------|
| Ice Cream Sundae | $3.99  | 3 min     |
| Apple Pie        | $2.99  | 2 min     |
| Brownie          | $3.49  | 2 min     |
| Churros          | $3.99  | 4 min     |

### Combos
| Product          | Price   | Prep Time |
|-----------------|---------|-----------|
| Classic Combo    | $9.99   | 8 min     |
| Double Combo     | $12.99  | 10 min    |
| Chicken Combo    | $11.49  | 9 min     |
| Kids Combo       | $6.99   | 6 min     |

## Re-seeding

To reset and re-seed the database:

```bash
# Docker
docker-compose exec app php artisan migrate:fresh --seed

# Local
php artisan migrate:fresh --seed
```

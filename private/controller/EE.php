<?php

class EE_controller extends controller{
	public function __construct(){
		// populate a mock database 
		$this->model = new EE_model;
			$names = array("Aaliyah", "Aaralyn", "Abigail", "Abril", "Adalyn", "Aditi",
         "Agustina", "Aiden", "Ajani", "Aleah", "Alejandro", "Alex", "Alexa",
         "Alexander", "Alexandra", "Alexis", "Alice", "Alma", "Alondra",
         "Alysha", "Alyson", "Amanda", "Amelia", "Amy", "Ana", "Anabelle",
         "Andrea", "Angel", "Angela", "Angeline", "Anjali", "Anna", "Annie",
         "Antoine", "Antonella", "Antonio", "Anya", "Aria", "Ariana", "Armaan",
         "Arnav", "Arthur", "Arushi", "Aryan", "Ashley", "Aubree", "Austin",
         "Ava", "Averie", "Avery", "Ayla", "Barbara", "Beatriz", "Beau",
         "Beckett", "Benjamin", "Bentley", "Bernardo", "Brayden", "Brianna",
         "Brielle", "Brooke", "Brooklyn", "Brooklynn", "Bruno", "Bryce",
         "Caden", "Caleb", "Callie", "Camden", "Cameron", "Camila", "Carlos",
         "Carter", "Casey", "Cash", "Catalina", "Chana", "Charles", "Charlie",
         "Charlotte", "Chase", "Chaya", "Chedeline", "Chloe", "Christian",
         "Claire", "Clark", "Clarke", "Cohen", "Connor", "Cooper", "Danica",
         "Daniel", "Daniela", "Davi", "David", "Dawson", "Declan", "Deven",
         "Diego", "Diya", "Dominic", "Dorothy", "Drake", "Drew", "Dylan",
         "Eduarda", "Edward", "Eli", "Elijah", "Elizabeth", "Ella", "Elle",
         "Ellie", "Elliot", "Elliott", "Elly", "Emerson", "Emersyn", "Emilia",
         "Emiliano", "Emily", "Emma", "Emmanuel", "Emmett", "Eric", "Esther",
         "Ethan", "Evan", "Evelyn", "Evens", "Ezra", "Felicity", "Felipe",
         "Felix", "Fernanda", "Fiona", "Florence", "Florencia", "Francisco",
         "Gabriel", "Gabriela", "Gabrielle", "Gage", "Gavin", "Genesis",
         "Georgia", "Giovanna", "Grace", "Gracie", "Grayson", "Griffin",
         "Guilherme", "Gus", "Hailey", "Haley", "Hannah", "Harper", "Harrison",
         "Hayden", "Henry", "Hudson", "Hunter", "Ian", "Iker", "Isaac",
         "Isabella", "Isaiah", "Ishaan", "Isidora", "Isla", "Islande",
         "Izabella", "Jace", "Jack", "Jackson", "Jacob", "Jada", "Jaden",
         "Jaelyn", "James", "Jameson", "Jase", "Jason", "Jax", "Jaxon",
         "Jaxson", "Jayden", "Jennifer", "Jeremiah", "Jessica", "Jimena",
         "John", "Jonah", "Jordyn", "Joseph", "Joshua", "Josiah", "Juan",
         "Judeline", "Julia", "Julieta", "Juliette", "Justin", "Kai", "Kaiden",
         "Kamila", "Kate", "Katherine", "Kathryn", "Kavya", "Kayla", "Keira",
         "Kevin", "Kingston", "Kinsley", "Kole", "Kyleigh", "Landon", "Laura",
         "Lauren", "Lautaro", "Layla", "Leah", "Leonardo", "Levi", "Lexi",
         "Liam", "Lily", "Lincoln", "Linda", "Logan", "London", "Lucas",
         "Luciana", "Luis", "Luke", "Luz", "Lydia", "Lylah", "Macie",
         "Mackenzie", "Madelyn", "Madison", "Maggie", "Maite", "Malcolm",
         "Manuela", "Marcus", "Margaret", "Maria", "Mariana", "Marley",
         "Martina", "Mary", "Mason", "Mateo", "Matheus", "Matthew",
         "Maximiliano", "Meredith", "Mia", "Micaela", "Michael", "Miguel",
         "Mila", "Milagros", "Miriam", "Molly", "Morgan", "Moshe", "Muhammad",
         "Mya", "Nash", "Nate", "Nathan", "Nathaniel", "Neil", "Nevaeh",
         "Neveah", "Nicole", "Nikhil", "Nisha", "Nishi", "Noah", "Nolan",
         "Oliver", "Olivia", "Olivier", "Owen", "Paige", "Paisley", "Parker",
         "Patricia", "Paula", "Pedro", "Peter", "Peterson", "Peyton", "Piper",
         "Quinn", "Rachel", "Rafael", "Rebekah", "Regina", "Renata", "Ricardo",
         "Richard", "Riley", "Riya", "Robert", "Rodrigo", "Rohan", "Romina",
         "Rosalie", "Rose-Merline", "Ruby", "Ruhi", "Ryan", "Ryder", "Ryker",
         "Ryleigh", "Sadie", "Samantha", "Samuel", "Santiago", "Santino",
         "Sara", "Sarah", "Savannah", "Sawyer", "Scarlett", "Sebastian",
         "Selena", "Serena", "Serenity", "Seth", "Shreya", "Simon", "Sofia",
         "Sophia", "Sophie", "Stanley", "Stella", "Stevenson", "Summer",
         "Suraj", "Susan", "Tanner", "Taylor", "Tessa", "Theo", "Thiago",
         "Thomas", "Tianna", "Tristan", "Turner", "Ty", "Tye", "Tyler",
         "Valentina", "Valeria", "Vicente", "Victoria", "Violet", "Widelene",
         "William", "Wilson", "Wyatt", "Xavier", "Ximena", "Zoe", "Zoey");
		 $emails = array();
		 $uids = array();
		 foreach($names as $name){
			$emails[] = $name."@emailservice.com";
			$uids[] = $name."_uid";
		 }
		
		 $inc = 0;
		 while($inc< count($names)){
			$inc = $inc + 1;
			$followers = $this->get_rand_follow($uids);
			$following = $this->get_rand_follow($uids);
			
			$eno = rand(0, count($names));
			$this->model->populate_users($names[$eno], $emails[$eno], $uids[$eno], $followers, $following);
			$favs = $this->get_rand_follow($uids);
			$this->model->populate_lits($uids[$eno], $favs, "A post", "Some content here", "Some tags here ");
			$favs = $this->get_rand_follow($uids);
			$this->model->populate_lits($uids[$eno], $favs, "Another post", "Some content here", "Some tags here ");
			$favs = $this->get_rand_follow($uids);
			$this->model->populate_lits($uids[$eno], $favs, "yet Another post", "Some content here", "Some tags here ");
			echo "User wth 3 lits inserted <br />";
		
		}
		 echo "All done";
		
	}
	private function get_rand_follow($uids){
		$rands = array_rand($uids, 12);
		$ds = array();
		foreach($rands as $no){
			$ds[] = $uids[$no];
		}
		$rands = implode(" ", $ds);
		return $rands;
	}
}
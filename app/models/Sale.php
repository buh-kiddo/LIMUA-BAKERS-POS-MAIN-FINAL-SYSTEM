<?php 


/**
 * sales class
 */
class Sale extends Model
{
	
	protected $table = "sales";

	protected $allowed_columns = [

				'barcode',
				'receipt_no',
				'user_id',
				'description',
				'qty',
				'amount',
				'total',
				'date',
			];

	public function validate($DATA)
	{
		$this->errors = array();

		//check description
		if(empty($DATA['description']))
		{
			$this->errors['description'] = "A description is required";
		}
		
		//check qty
		if(empty($DATA['qty']))
		{
			$this->errors['qty'] = "A quantity is required";
		}
		
		//check amount
		if(empty($DATA['amount']))
		{
			$this->errors['amount'] = "An amount is required";
		}

		if(count($this->errors) == 0)
		{
			return true;
		}

		return false;
	}

	public function getTotal()
	{
		try {
			$result = $this->query("SELECT COUNT(*) as total FROM " . $this->table);
			return $result[0]['total'] ?? 0;
		} catch (PDOException $e) {
			return 0;
		}
	}

}
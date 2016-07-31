<?php
namespace Tlab\Libraries;

/*****************************************************
 * Classe para paginação
 *
 * definir:
 * _PAGINATE_NEXT
 * _PAGINATE_PREVIOUS
 * _PAGINATE_GO_TO
 *
 *
 *
 *****************************************************/
class Paginator{
	public $items_per_page;
	public $items_total;
	public $current_page;
	public $num_pages;
	public $mid_range;
	public $low;
	public $high;
	public $limit;
	public $pageLinks;
	private $default_ipp = _CONFIG_REG_PAGE;
	private $_url;
	private $_params;

	function __construct($url, $params)
	{
		
		unset($params['lang']);
		unset($params['controller']);
		unset($params['action']);
		
		if(isset($params['page']))
			$this->current_page = $params['page'];
		else
			$this->current_page = 1;
		$this->mid_range = 7;
		
		if(isset($params['ipp']))
			$this->items_per_page = $params['ipp'];
		else{
			$this->items_per_page = $this->default_ipp;
			$params['ipp'] = $this->default_ipp;
		}	
		$this->pageLinks = array();
		$this->_url = $url;
		$this->_params = $params;
		
	}

	function paginate()
	{
		
		if(!is_numeric($this->items_per_page) || $this->items_per_page <= 0) $this->items_per_page = $this->default_ipp;
		$this->num_pages = ceil($this->items_total/$this->items_per_page);
		
		if($this->current_page < 1 || !is_numeric($this->current_page)) $this->current_page = 1;
		if($this->current_page > $this->num_pages) $this->current_page = $this->num_pages;
		$prev_page = $this->current_page-1;
		$next_page = $this->current_page+1;

		
		if($this->num_pages > 10)
		{
			//PRIMEIRA PÁGINA
			if($this->current_page != 1 && $this->items_total >= 10)
				$this->pageLinks[0] = array('caption'=>'&laquo;'._PAGINATE_PREVIOUS, 'link'=>$this->_url.'?'.$this->_makeParams($prev_page), 'alt'=>_PAGINATE_PREVIOUS);
			else
				$this->pageLinks[0] = array('caption'=>_PAGINATE_PREVIOUS, 'link'=>'', 'alt'=>'');
			

			$this->start_range = $this->current_page - floor($this->mid_range/2);
			$this->end_range = $this->current_page + floor($this->mid_range/2);

			if($this->start_range <= 0)
			{
				$this->end_range += abs($this->start_range)+1;
				$this->start_range = 1;
			}
			if($this->end_range > $this->num_pages)
			{
				$this->start_range -= $this->end_range-$this->num_pages;
				$this->end_range = $this->num_pages;
			}
			$this->range = range($this->start_range,$this->end_range);

			for($i=1;$i<=$this->num_pages;$i++)
			{
				if($this->range[0] > 2 && $i == $this->range[0])
					$this->pageLinks[] = array('caption'=>'...', 'link'=>'', 'alt'=>'');	
				
				 
				// loop through all pages. if first, last, or in range, display
				if($i==1 || $i==$this->num_pages || in_array($i,$this->range))
				{
					if($i == $this->current_page){
						$temp = sprintf(_PAGINATE_GO_TO,$i, $this->num_pages);
						$this->pageLinks[] = array('caption'=>$i, 'link'=>'', 'alt'=>$temp);
					}else{
						$temp = sprintf(_PAGINATE_GO_TO,$i, $this->num_pages);
						$this->pageLinks[] = array('caption'=>$i, 'link'=>$this->_url.'?'.$this->_makeParams($i), 'alt'=>$temp);
					}
				}
				
				if($this->range[$this->mid_range-1] < $this->num_pages-1 && $i == $this->range[$this->mid_range-1])
					$this->pageLinks[] = array('caption'=>'...', 'link'=>'', 'alt'=>'');
				
				
			}
			
			//ULTIMA PÁGINA
			if((($this->current_page != $this->num_pages && $this->items_total >= 10)))
				$this->pageLinks[] = array('caption'=>_PAGINATE_NEXT, 'link'=>$this->_url.'?'.$this->_makeParams($next_page), 'alt'=>'');
			else
				$this->pageLinks[] = array('caption'=>_PAGINATE_NEXT, 'link'=>'', 'alt'=>'');
			
				
			
		}
		else
		{
			//PRIMEIRA PÁGINA
			if($this->current_page != 1 && $this->items_total >= 10)
				$this->pageLinks[0] = array('caption'=>_PAGINATE_PREVIOUS, 'link'=>$this->_url.'?'.$this->_makeParams($prev_page), 'alt'=>_PAGINATE_PREVIOUS);
			else
				$this->pageLinks[0] = array('caption'=>_PAGINATE_PREVIOUS, 'link'=>'', 'alt'=>'');
				
			for($i=1;$i<=$this->num_pages;$i++)
			{
				if($i == $this->current_page)
					$this->pageLinks[] = array('caption'=>$i, 'link'=>'', 'alt'=>'');
				else
					$this->pageLinks[] = array('caption'=>$i, 'link'=>$this->_url.'?'.$this->_makeParams($i), 'alt'=>'');
				
			}
			
			//ULTIMA PÁGINA
			if((($this->current_page != $this->num_pages && $this->items_total >= 10)))
				$this->pageLinks[] = array('caption'=>_PAGINATE_NEXT, 'link'=>$this->_url.'?'.$this->_makeParams($next_page), 'alt'=>'');
			else
				$this->pageLinks[] = array('caption'=>_PAGINATE_NEXT, 'link'=>'', 'alt'=>'');
				
		}
		$this->low = ($this->current_page-1) * $this->items_per_page;
		$this->high = ($this->current_page * $this->items_per_page)-1;
		if($this->low >= 0)
			$this->limit = " LIMIT $this->low,$this->items_per_page";
		else
			$this->limit = '';
	}

	
	private function _makeParams($page){
			$_temp_url = array();
			foreach($this->_params as $key=>$value){
				if($key == 'page'){ $_temp_url[] = $key.'='.$page; continue;}
				if($key == 'ipp'){ $_temp_url[] = $key.'='.$this->items_per_page; continue;}
				$_temp_url[] = $key.'='.$value;
			}
		if(!isset($this->_params['page']))
			$_temp_url[] = 'page'.'='.$page;
			
		if(!isset($this->_params['ipp']))
			$_temp_url[] = 'ipp'.'='.$this->items_per_page;
		
			
		return implode('&', $_temp_url);
	}
	
	
	
	function display_pages()
	{
		return $this->pageLinks;
	}
}

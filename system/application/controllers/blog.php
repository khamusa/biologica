<?


class Blog extends Controller {

	function Blog() {
		
		parent::Controller();
		
		$this->load->helper('url');
		$this->load->helper('form');
		
		
		$this->load->scaffolding('comments');
	}
	
	function index() {
		$this->output->set_header("charset:utf-8");
		$data['title'] = "Blog do Samuca";
		$data['logo'] = "Últimos Posts";
		$data['query'] = $this->db->get('entries');
		$this->load->view('blog_view', $data);
	}
	
	function comments() {
		$this->output->set_header("charset:utf-8");
		
		$data['title'] = "Comentários do Samuca";
		$data['logo'] = "Comentários para o post tal";
		$this->db->where('entry_id',$this->uri->segment(3));
		$data['query'] = $this->db->get('comments');
		$this->load->view('comment_view', $data);
		
		
	}
	
	function comment_insert() {
		$insert['entry_id'] = $_POST['entry_id'];
		$insert['author'] = mysql_real_escape_string($_POST['author']);
		$insert['body'] = mysql_real_escape_string($_POST['body']);
		$this->db->insert('comments', $insert);
		redirect('blog/comments/'.$_POST["entry_id"]);
	}
}
?>
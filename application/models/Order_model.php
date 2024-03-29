<?php defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends CI_Model
{
    function get($id = null)
    {
        $this->db->from('t_order');
        if ($id != null) {
            $this->db->where('order_id', $id);
        }
        return $query = $this->db->get();
    }

    function invoice_no()
    {
        $sql = "SELECT MAX(MID(invoice,9,4)) AS invoice_no 
                FROM t_order 
                WHERE MID(invoice,3,6) = DATE_FORMAT(CURDATE(), '%y%m%d')";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $n = ((int)$row->invoice_no) + 1;
            $no = sprintf("%'.04d", $n);
        } else {
            $no = "0001";
        }
        return $invoice = "ID" . date('ymd') . $no;
    }

    function add($post)
    {
        $params = [
            // nama d db        => nama di inputan
            'lomba_id'  => $post['id'],
            'user_id'   => $this->session->userdata('user_id'),
            'invoice'   => '0',
        ];
        $this->db->insert('t_order', $params);
    }

    function del($table, $id)
    {
        $this->db->where($table, $id);
        $this->db->where('invoice', '0');
        $this->db->delete('t_order');
    }

    function update_invoice($post)
    {
        $invoice    = $post['invoice'];
        $user_id    = $this->session->userdata('user_id');
        $sql = "UPDATE t_order SET invoice = '$invoice' WHERE user_id = '$user_id' AND invoice = '0'";
        $this->db->query($sql);
    }
}

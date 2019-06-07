<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sum', 'agent_id', 'agent_commission', 'type', 'user_id',
        'round_id', 'comment', 'bonus_sum', 'ext_id', 'confirmations',
    ];

    public function scopeDeposits($query)
    {
        return $query->where('type', 3);
    }

    public function scopeWithdraws($query)
    {
        return $query->where('type', 4);
    }

    public function scopeWins($query)
    {
        return $query->where('type', 2);
    }

    public function scopeLoses($query)
    {
        return $query->where('type', 1);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function token()
    {
        return $this->belongsTo(\App\Token::class);
    }

    public function getBtcSum()
    {
        return bcdiv($this->sum, 1000, 8);
    }

    public function getSum()
    {
        return round($this->sum, 4);
    }

    public function isConfirmed()
    {
        $minConfirmBtc = config('appAdditional.minConfirmBtc');

        if ($this->confirmations > ($minConfirmBtc - 1)) {
            return true;
        }

        return false;
    }

    public function getStatus()
    {
        if ($this->type == 3) {
            if ($this->isConfirmed()) {
                return 'Confirmed';
            } else {
                return 'Not confirmed';
            }
        } elseif ($this->type == 4) {
            if ($this->withdraw_status == 1) {
                return 'Success';
            } else {
                return 'Your withdrawal is pending approval';
            }
        }

        return 'Unknown';
    }

    public function confirmStatus()
    {
        if ($this->type == 3) {
            if ($this->isConfirmed()) {
                return true;
            } else {
                return false;
            }
        } elseif ($this->type == 4) {
            if ($this->withdraw_status == 1) {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }

    public function getDescription()
    {
        $html = '';

        if ($this->confirmStatus()) {
            $html = $html.'<span class="label label-success">CONFIRMED</span> ';
        } else {
            $html = $html.'<span class="label label-warning">PENDING</span> ';
        }

//        if ($this->type == 1) $html = $html . '<span class="label ' . $this->token->slot->category->css_class . '">' . $this->token->slot->category->name . '</span> <div class="pull-right"><i>Bet at <b>' . ucfirst($this->token->slot->name) . '</b></i></div>';
//        elseif ($this->type == 2) $html = $html . '<span class="label ' . $this->token->slot->category->css_class . '">' . $this->token->slot->category->name . '</span> <div class="pull-right"><i>Win at <b>' . ucfirst($this->token->slot->name) . '</b></i></div>';
//        elseif ($this->type == 3) $html = $html . '<div class="pull-right">DepositEvent</div>';
//        elseif ($this->type == 4) $html = $html . '<div class="pull-right">Withdraw</div>';
//        elseif ($this->type == 5) $html = $html . '<div class="pull-right">Bonus activation</div>';
//        elseif ($this->type == 6) $html = $html . '<div class="pull-right">Bonus cancellation</div>';
//        elseif ($this->type == 7) $html = $html . '<div class="pull-right">Bonus to real</div>';
//        elseif ($this->type == 8) $html = $html . '<div class="pull-right">Free spins add</div>';
//        elseif ($this->type == 9) $html = $html . '<span class="label ' . $this->token->slot->category->css_class . '">' . $this->token->slot->category->name . '</span> <span class="label label-info">FREE</span>  <div class="pull-right"><i>Bet at <b>' . ucfirst($this->token->slot->name) . '</b></i></div>';
//        elseif ($this->type == 10) $html = $html . '<span class="label ' . $this->token->slot->category->css_class . '">' . $this->token->slot->category->name . '</span> <span class="label label-info">FREE</span> <div class="pull-right"><i>Win at <b>' . ucfirst($this->token->slot->name) . '</b></i></div>';

        if ($this->type == 1) {
            $html = $html.'<div class="pull-right">Bet</div>';
        } elseif ($this->type == 2) {
            $html = $html.'<div class="pull-right">Win</div>';
        } elseif ($this->type == 3) {
            $html = $html.'<div class="pull-right">DepositEvent</div>';
        } elseif ($this->type == 5) {
            $html = $html.'<div class="pull-right">Bonus activation</div>';
        } elseif ($this->type == 6) {
            $html = $html.'<div class="pull-right">Bonus cancellation</div>';
        } elseif ($this->type == 7) {
            $html = $html.'<div class="pull-right">Bonus to real</div>';
        } elseif ($this->type == 8) {
            $html = $html.'<div class="pull-right">Free spins add</div>';
        } elseif ($this->type == 9) {
            $html = $html.'<div class="pull-right">FREE Bet</div>';
        } elseif ($this->type == 10) {
            $html = $html.'<div class="pull-right">FREE Win</div>';
        }

        return $html;
    }

    public function getAmount()
    {
        $html = '';

        if ($this->sum > 0) {
            $html = $html.'<span class="label label-success">';
        } else {
            $html = $html.'<span class="label label-danger">';
        }

        $html = $html.sprintf('%+.2f', round($this->sum, 2)).' mBtc</span>';

        return $html;
    }

    public function getBonusAmount()
    {
        $html = '';

        if ($this->bonus_sum > 0) {
            $html = $html.'<span class="label label-success">';
        } else {
            $html = $html.'<span class="label label-danger">';
        }

        $html = $html.sprintf('%+.2f', round($this->bonus_sum, 2)).' mBtc</span>';

        return $html;
    }

    public function getAdminStatus()
    {
        $status = $this->getStatus();

        if ($this->type == 4 and $this->withdraw_status == 0) {
            $status = 'Pending';
        }
        if ($this->type == 4 and $this->withdraw_status == -1) {
            $status = 'Frozen';
        }
        if ($this->type == 4 and $this->withdraw_status == 2) {
            $status = 'Sending';
        }
        if ($this->type == 4 and $this->withdraw_status == -1) {
            $status = 'Failed';
        }

        return $status;
    }

    public function getType()
    {
        switch ($this->type) {
            case 1:
                return 'Win';

                break;
            case 2:
                return 'Lose';

                break;
            case 3:
                return 'DepositEvent';

                break;
            case 4:
                return 'Withdraw';

                break;
            default:
                return 'Unknown';

                break;
        }
    }

    public function agent()
    {
        return $this->belongsTo(\App\User::class, 'agent_id', 'id');
    }
}

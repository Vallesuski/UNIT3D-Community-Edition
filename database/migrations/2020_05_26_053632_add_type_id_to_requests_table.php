<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

use App\Models\TorrentRequest;
use App\Models\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->integer('type_id')->index();
        });

        foreach (TorrentRequest::all() as $torrent_req) {
            $type_id = Type::where('name', '=', $torrent_req->type)->firstOrFail()->id;
            $torrent_req->type_id = $type_id;
            $torrent_req->save();
        }

        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};

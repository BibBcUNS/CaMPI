import os            # path.*, mkdir, listdir, etc 
import sys           # argv for processing script arguments
import shutil        # shell utils (copy, move, rmtree...)

#import subprocess    # for running system commands (mx, i2id, etc)
import ConfigParser  # for reading config file

from opac_util import run_command, error, emptydir, APP_DIR, LOCAL_DATA_DIR, setup_logger, unique_sort_files, subprocess

#COPY_DIR = os.path.join(LOCAL_DATA_DIR, 'bases', DB_NAME, 'db/original')
DB_NAME = sys.argv[1]

DESTINO_1 = os.path.join(LOCAL_DATA_DIR + '/bases/' + DB_NAME)
DESTINO = os.path.join(DESTINO_1, 'db/original')

FUENTE_1 ='c:/campi/catalis/bases/biblio/'
FUENTE = os.path.join(FUENTE_1, DB_NAME)

shutil.copy(os.path.join(FUENTE,'biblio.mst'),DESTINO)
shutil.copy(os.path.join(FUENTE,'biblio.xrf'),DESTINO)
